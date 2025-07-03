<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\District;
use App\Models\Organisation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Utils;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DuAgentCreated;


class AgentController extends AdminController
{
    protected $title = 'District Union Agents';

    protected function grid()
    {
        $grid = new Grid(new User());

        // Eager-load DU’s org + district, and only DU-Agent users
        $query = $grid->model()
             ->with(['organisation.district'])
             ->whereHas('roles', fn($q) => $q->where('slug','du-agent'));

        // District-Union sees only their own agents
        if (Admin::user()->isRole('district-union')) {
             $query->where('organisation_id', Admin::user()->organisation_id);
        }

         // 3) **NEW**: order by creation date descending
          $query->orderBy('created_at', 'desc');
        // Columns
        $grid->column('created_at', __('Registered Date'))->display(
            fn($x) => Utils::my_date($x)
        )->sortable();

        $grid->column('Username')->display(
            fn() => $this->first_name . ' ' . $this->last_name
        );

        $grid->column('email', 'Email');

        $grid->column('Attached District')->sortable()->display(
            fn() => optional($this->organisation->district)->name ?? '-'
        );

        $grid->column('DU Name')->sortable()->display(
            fn() => optional($this->organisation)->name ?? '-'
        );

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('email', 'Email');
            $filter->like('first_name', 'First Name');
            $filter->like('last_name', 'Last Name');
            $filter->equal('organisation.district_id', 'Attached District')
                   ->select(District::orderBy('name','asc')->pluck('name','id'));
            $filter->equal('organisation_id', 'DU Name')
                   ->select(Organisation::where('relationship_type','du')
                                            ->orderBy('name','asc')
                                            ->pluck('name','id'));
        });

        $grid->disableBatchActions();
        $grid->actions(fn($actions) => null);

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new User());

        $form->text('first_name', 'First Name')->rules('required');
        $form->text('last_name',  'Last Name')->rules('required');
        $form->email('email',     'Email')
             ->rules('required|email|unique:users,email,{{id}}');

        // Required on create, optional on update
        $form->password('password', 'Password')
             ->rules($form->isCreating() ? 'required|min:4' : 'nullable|min:4');

        // Tie this agent back to your DU automatically
        $form->hidden('organisation_id')->default(Admin::user()->organisation_id);

       $form->saving(function(Form $form) {
        // Ensure username = email
        $form->model()->username = $form->input('email');

        // Pick a plaintext password
            if ($form->isCreating()) {
                $plain = $form->input('password') ?: Str::random(8);
            } else {
                $plain = $form->input('password');
            }

            // Assign to model (mutator will hash)
            if ($plain) {
                $form->model()->password = $plain;
                // stash for email
                session(['new_du_agent_password' => $plain]);
                }
            });

        $form->saved(function(Form $form) {
        $agent = $form->model();

        // 1) attach role
        $agent->assignRole('du-agent');

        // 2) send email
        $plain = session('new_du_agent_password', null);

        if ($plain && $agent->email) {
            Mail::to($agent->email)
                ->send(new DuAgentCreated($agent, $plain));
        }

                // 3) clear it out
                session()->forget('new_du_agent_password');
            });

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id');
        $show->field('first_name', 'First Name');
        $show->field('last_name',  'Last Name');
        $show->field('email',      'Email');
        $show->field('organisation.name', 'DU Name');

        $show->field('Attached District')->as(function() {
            return optional($this->organisation->district)->name ?? '-';
        });

        $show->field('created_at', 'Registered Date')->as(function($x) {
            return Utils::my_date($x);
        });

        return $show;
    }
    
}
