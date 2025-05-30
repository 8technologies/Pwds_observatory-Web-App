<?php

namespace App\Admin\Controllers;

use App\Models\Job;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JobController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Job';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
{
    $grid = new Grid(new Job());

    $user = Admin::user();
    if ($user->inRoles(['basic', 'pwd'])) {
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->disableCreateButton();
    }

    // Filters for the grid
    $grid->filter(function ($filter) {
        $filter->disableIdFilter();
        $filter->equal('type', 'Job Type')->select(Job::pluck('type', 'type'));
        $filter->equal('location', 'Location')->select(Job::pluck('location', 'location'));
        $filter->equal('hiring_firm', 'Hiring Firm')->select(Job::pluck('hiring_firm', 'hiring_firm'));
        $filter->equal('minimum_academic_qualification', 'Minimum Academic Qualification')->select(Job::pluck('minimum_academic_qualification', 'minimum_academic_qualification'));
        $filter->equal('required_experience', 'Years Of Experience')->select(Job::pluck('required_experience', 'required_experience'));
        $filter->date('deadline', 'Filter By Deadline')->date();
    });

    $grid->quickSearch('title', 'location', 'type', 'minimum_academic_qualification', 'required_experience', 'hiring_firm', 'deadline')->placeholder('Search...');

    $grid->actions(function (Grid\Displayers\Actions $actions) use ($user) {
        $job = $actions->row;
        if ($job->user_id !== $user->id) {
            $actions->disableEdit();
            $actions->disableDelete();
        }
    });

    $grid->model()->orderBy('created_at', 'desc');
    $grid->disableBatchActions();

    $grid->column('created_at', __('Published at'))->display(function ($x) {
        return Utils::my_date($x);
    })->sortable();
    
    $grid->column('user_id', __('Publisher'))->display(function ($user_id) {
        return \App\Models\User::find($user_id)->name;
    });
    
    $grid->column('title', __('Title'));
    $grid->column('location', __('Location'));
    $grid->column('type', __('Type'));
    $grid->column('minimum_academic_qualification', __('Minimum academic qualification'));
    $grid->column('required_experience', __('Required experience'));
    $grid->column('hiring_firm', __('Hiring firm'));
    $grid->column('deadline', __('Deadline'));

    $grid->column('status', __('Status'))->display(function () {
        // Determine the status
        $status = now()->greaterThan($this->deadline) ? 'Expired' : 'Active';
    
        // Set color based on the status
        $color = $status === 'Expired' ? 'red' : 'green';
    
        // Return HTML with inline styling for color
        return "<span style='color: white; background-color: {$color}; padding: 5px 10px; border-radius: 5px;'>{$status}</span>";
    })->sortable();
    

    return $grid;
}

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail()
    {
        $jobs = Job::paginate(8);
        $show = new Show($jobs);

        return view('admin.jobs.show', [
            'jobs' => $jobs
        ]);

        // $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        // $show->field('user_id', __('User id'));
        $show->field('title', __('Title'));
        $show->field('location', __('Location'));
        $show->field('description', __('Description'))->as(function ($description) {
            return strip_tags($description);
        });
        $show->field('type', __('Type'));
        $show->field('minimum_academic_qualification', __('Minimum academic qualification'));
        $show->field('required_experience', __('Required experience'));
        $show->field('photo', __('Photo'))->image();
        $show->field('how_to_apply', __('How to apply'));
        $show->field('hiring_firm', __('Hiring firm'));
        $show->field('deadline', __('Deadline'));

        return $show;
    }

    protected function search_job()
    {
        $jobs = Job::all();
        $show = new Show($jobs);

        return view('admin.jobs.show', [
            'jobs' => $jobs
        ]);

        // $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        // $show->field('user_id', __('User id'));
        $show->field('title', __('Title'));
        $show->field('location', __('Location'));
        $show->field('description', __('Description'))->as(function ($description) {
            return strip_tags($description);
        });
        $show->field('type', __('Type'));
        $show->field('minimum_academic_qualification', __('Minimum academic qualification'));
        $show->field('required_experience', __('Required experience'));
        $show->field('photo', __('Photo'))->image();
        $show->field('how_to_apply', __('How to apply'));
        $show->field('hiring_firm', __('Hiring firm'));
        $show->field('deadline', __('Deadline'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Job());

        $form->hidden('user_id');
        $form->text('title', __('Title'));
        $form->text('location', __('Location'));
        $form->date('deadline', __('Deadline (Available before)'))->rules('required');
        $form->select('type', __('Type'))->options([
            'fulltime' => 'Full time',
            'parttime' => 'Part time',
            'contract' => 'Contract',
            'internship' => 'Internship',
            'volunteer' => 'Volunteer',
            'remote' => 'Remote',
            'Other' => 'Other',
        ])->rules('required');
        $form->select('minimum_academic_qualification', __('Minimum academic qualification'))
            ->options([
                'None' => 'None - (Not educated at all)',
                'Below primary' => 'Below primary - (Did not complete P.7)',
                'Primary' => 'Primary - (Completed P.7)',
                'UCE' => 'O Level- (Completed S.4)',
                'UACE' => 'A Level - (Completed S.6)',
                'Diploma-Certificate' => 'Diploma - (Certificate)',
                'Bachelor' => 'Bachelor - (Degree)',
                'Post-Graduate' => 'Post-Graduate',
                'Masters' => 'Masters',
                'PhD' => 'PhD',
            ])->rules('required');
        $form->text('required_experience', __('Required experience'));
        $form->image('photo', __('Photo'));
        $form->quill('how_to_apply', __('How to apply'));
        $form->text('hiring_firm', __('Hiring firm'));
        $form->quill('description', __('Description'));

        $form->saving(function (Form $form) {
            $form->user_id = auth('admin')->user()->id;
            $auth_user = Admin::user(); // Get the currently authenticated admin user

            // Find the job being updated
            $job = Job::find($form->model()->id);

            // if ($job->user_id !== $auth_user->id) {
            //     throw new \Exception("You are not authorized to update this job.");
            // }
        });;   // $form->updating(function (Form $form) {
        //     $auth_user = Admin::user(); // Get the currently authenticated admin user

        //     // Find the job being updated
        //     $job = Job::find($form->model()->id);

        //     if ($job->user_id !== $auth_user->id) {
        //         throw new \Exception("You are not authorized to update this job.");
        //     }
        // })

        return $form;
    }
}
