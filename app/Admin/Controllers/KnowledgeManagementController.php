<?php

namespace App\Admin\Controllers;

use App\Models\Job;
use App\Models\Location;
use App\Models\Project;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;

class KnowledgeManagementController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Knowledge management';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */



    protected function grid()
    {

        $grid = new Grid(new Project());
        $user = auth()->user();
        $grid->model()->where('user_id', $user->id);
   
        $grid->disableFilter();
        $grid->disableBatchActions();
        $grid->quickSearch('title')->placeholder('Search by Job Title');
        $grid->model()->orderBy('id', 'desc');

        $grid->column('title', __('Project Title'))->sortable();  
        $grid->column('code', __('Project Code/ID'));
        $grid->column('start_date', __('Start Date'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('end_date', __('End Date'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('funding_source', __('Funding Source'));
         

        return $grid;
    }
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
   
        protected function detail($id)
        {
            $show = new Show(Project::findOrFail($id));

            $show->field('title', __('Project Title'));
            $show->field('code', __('Project Code/ID'));
            $show->field('description', __('Project Description'));
            $show->field('start_date', __('Start Date'));
            $show->field('end_date', __('End Date'));
            $show->field('funding_source', __('Funding Source'));
            $show->field('budget', __('Budget Allocation'));
            $show->field('beneficiaries', __('Target Beneficiaries'));

            // Option A: call the relation name directly
            $show->milestones(__('Milestones'), function (Grid $grid) {
                $grid->disableCreateButton();       // optional
                $grid->disableActions();            // optional
                $grid->model()->orderBy('start_date');

                $grid->column('title', __('Milestone Title'));
                $grid->column('description', __('Milestone Description'))->limit(80);
                $grid->column('start_date', __('Planned Start Date'));
                $grid->column('end_date', __('Planned End Date'));
                $grid->column('responsible_person', __('Responsible Person/Department'));
                $grid->column('status', __('Status'));
                $grid->column('completion_date', __('Completion Date'));
                $grid->column('milestone_progress', __('Progress'))->display(function ($v) {
                    return $v . '%';
                });
                $grid->column('attachments', __('Supporting Evidence/Documentation'))
                    ->display(function ($paths) {
                        // adapt depending on your storage format
                        if (!$paths) return '';
                        $items = is_array($paths) ? $paths : explode(',', $paths);
                        return collect($items)->map(function ($p) {
                            $url = Storage::url($p);
                            return "<a href='{$url}' target='_blank'>View</a>";
                        })->implode(' | ');
                    })/* ->unescape() */;
            });

            // Option B: equivalently
            // $show->relation('milestones', __('Milestones'), function (Grid $grid) { ... });

            return $show;
        }



    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Project());
        $user = auth()->user();

        $form->text('title', __('Project Title'))->rules('required');
        $form->text('code', __('Project Code/ID'))->rules('required');
        $form->text('description', __('Project Description'))->rules('required');
        $form->date('start_date', __('Start Date'))->rules('required');
        $form->date('end_date', __('End Date'))->rules('required');
        $form->select('funding_source', __('Funding Source'))->rules('required')
            ->options([
                    'government' => 'Government',
                    'donor' => 'Donor',
                    'self-funded' => 'Self-funded',
                    'other' => 'Other',
                ]);
        $form->number('budget', __('Budget Allocation'))->rules('required')->min(0);
        
        $form->text('beneficiaries', __('Target Beneficiaries'))->rules('required');
        $form->hidden('user_id', __('Target Beneficiaries'))->default($user->id);

        $form->hasMany('milestones', 'Milestones', function (Form\NestedForm $form) {
            $form->text('title', __('Milestone Title'))->rules('required');
            $form->textarea('description', __('Milestone Description'))->rules('required');
            $form->date('start_date', __('Planned Start Date'))->rules('required');
            $form->date('end_date', __('Planned End Date'))->rules('required');
            $form->text('responsible_person', __('Responsible Person/Department'))->rules('required');
            
            $form->select('status', __('Status'))->rules('required')
                ->options([
                    'Not_started' => 'Not started',
                    'Ongoing' => 'Ongoing',
                    'Completed' => 'Completed',
                    'Delayed' => 'Delayed',
                    'Cancelled' => 'Cancelled',
                ])
                ->when('Completed', function($form){
                    $form->text('completion_date', __('Completion Date'))->rules('required');
                });
                // ->when(3, function ($form) {
                //     // This closure will only be executed if the radio value is 1
                //     $form->file('concept_note', __('Concept Note File'))
                //         ->rules('mimes:pdf,doc,docx')
                //         ->help('Please upload your concept note.');
                // });
                
                $form->number('milestone_progress', __('Progress'))->rules('required');
            
            
            $form->multipleFile('attachments', __('Supporting Evidence/Documentation'))
                ->help('upload fies of jpg,jpeg,png,pdf formats ')
                // ->rules('file|mimes:pdf,jpg,jpeg,png|max:5120') // 5MB max
                ->removable();
        } );    
        
        return $form;
    }
}
