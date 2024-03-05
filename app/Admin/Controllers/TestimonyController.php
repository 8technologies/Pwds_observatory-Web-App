<?php

namespace App\Admin\Controllers;

use App\Models\Testimony;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TestimonyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Testimony';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Testimony());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('user_id', __('User id'));
        $grid->column('photo', __('Photo'));
        $grid->column('status', __('Status'));
        $grid->column('contact', __('Contact'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Testimony::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('user_id', __('User id'));
        $show->field('description', __('Description'));
        $show->photo(__('Photo'))->image();
        $show->field('attachments', __('Attachments'));
        $show->field('status', __('Status'));
        $show->field('contact', __('Contact'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Testimony());

        $form->text('title', __('Title'));
        $form->image('photo', __('Photo'));
        $form->text('status', __('Status'))->default('pending');
        $form->text('contact', __('Contact'));
        $form->quill('description', __('Description'));
        $form->multipleFile('attachments', __('Attachments'));

        $form->saving(function (Form $form) {
            $form->user_id = auth()->user()->id;
        });

        return $form;
    }
}
