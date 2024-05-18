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
        $show = new Show(Job::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user_id', __('User id'));
        $show->field('title', __('Title'));
        $show->field('location', __('Location'));
        $show->field('description', __('Description'));
        $show->field('type', __('Type'));
        $show->field('minimum_academic_qualification', __('Minimum academic qualification'));
        $show->field('required_experience', __('Required experience'));
        $show->field('photo', __('Photo'));
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
                'Secondary' => 'Secondary - (Completed S.4)',
                'A-Level' => 'Advanced level - (Completed S.6)',
                'Bachelor' => 'Bachelor - (Degree)',
                'Masters' => 'Masters',
                'PhD' => 'PhD',
            ])->rules('required');
        $form->text('required_experience', __('Required experience'));
        $form->image('photo', __('Photo'));
        $form->textarea('how_to_apply', __('How to apply'));
        $form->text('hiring_firm', __('Hiring firm'));
        $form->quill('description', __('Description'));

        $form->saving(function (Form $form) {
            $form->user_id = auth('admin')->user()->id;
        });

        return $form;
    }
}
