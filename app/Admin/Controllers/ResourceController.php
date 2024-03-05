<?php

namespace App\Admin\Controllers;

use App\Models\Resource;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ResourceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Resource';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Resource());
        $grid->model()->latest();
        $grid->quickSearch('title')->placeholder('Search by Title');
        $grid->filter(function ($f) {
            // Remove the default id filter
            $f->disableIdFilter();
            $f->between('date_of_publication', 'Filter by Date of publication')->date();
            $f->where( function ($query) {
                $query->where('type', 'like', "%{$this->input}%")
                    ->orWhere('other_type', 'like', "%{$this->input}%");
            }, 'Filter by Type');
            $f->where( function ($query) {
                $query->where('author', 'like', "%{$this->input}%");
            }, 'Filter by Author');
        });

        $grid->column('title', __('Title'));
        $grid->column('type', __('Type'))->display(function ($type) {
            if ($this->other_type) {
                return $this->other_type;
            }
            return $type;
        });
        $grid->column('date_of_publication', __('Date of publication'));
        $grid->column('author', __('Author'));
        
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
        $show = new Show(Resource::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('type', __('Type'));
        // $show->field('other_type', __('Other type'));
        $show->field('date_of_publication', __('Date of publication'));
        $show->field('description', __('Description'));
        $show->field('author', __('Author'));
        $show->field('attachments', __('Attachments'));
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
        $form = new Form(new Resource());

        $form->text('title', __('Title'))->rules('required');
        $form->select('type', __('Type'))->options(['multimedia'=>'Multimedia', 'publication'=>'Publication', 'other'=>'Other','dataset'=>'Dataset'])
        ->when('=','other', function (Form $form)  {
            $form->text('other_type', __('Other type'));
        })->rules('required');
        // $form->text('other_type', __('Other type'));
        $form->date('date_of_publication', __('Date of publication'))->default(date('Y-m-d'));
        $form->text('author', __('Author'));
        $form->image('cover_photo', __('Cover photo'));
        $form->quill('description', __('Description'))->rules('required');

        $form->multipleFile('attachments', __('Attachments'))
        ->help("You can also attach muliple files");

        return $form;
    }
}
