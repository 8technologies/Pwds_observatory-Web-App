<?php

namespace App\Admin\Controllers;

use App\Models\DataImport;
use App\Models\District;
use App\Models\Organisation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DataImportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Data Importation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        
        
           
        $grid = new Grid(new DataImport());

        //disableIdFilter
        $grid->disableFilter();

        $user = Admin::user();

        $organisation = Organisation::find(Admin::user()->organisation_id);
        if ($user->inRoles(['nudipu', 'administrator'])) {
            $grid->model()->orderBy('created_at', 'desc');
        } elseif ($user->isRole('district-union')) {
            $grid->model()->where('district', $organisation->district_id)->orderBy('created_at', 'desc');
        } else if ($user->isRole('opd')) {
            $grid->model()->where('opd_id', $organisation->id)->orderBy('created_at', 'desc');
        }



        $grid->model()->where('user_id', $user->id);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        
        $grid->column('title', __('Title'));
        $grid->column('file', __('File'));
        $grid->column('district', __('District'));
        $grid->column('processed', __('Processed'));
        $grid->column('has_error', __('Has error'));
        $grid->column('error_message', __('Error message'));
        $grid->column('total_records', __('Total records'));
        $grid->column('total_imported', __('Total imported'));
        $grid->column('total_failed', __('Total failed'));
        $grid->column('import', __('Import'))
            ->display(function ($import) {
                //if already
                $url =url('import-people-process?id='.$this->id);
                if ($this->processed == 'Yes') {
                    //btn open in new tab, add  btn classes
                    return "<a href='{$url}' target='_blank'>Import Again</a>";
                }
                return "<a target='_blank' href='{$url}'>Import</a>";
            });

       

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
        $show = new Show(DataImport::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user_id', __('User id'));
        $show->field('title', __('Title'));
        $show->field('file', __('File'));
        $show->field('district', __('District'));
        $show->field('processed', __('Processed'));
        $show->field('has_error', __('Has error'));
        $show->field('error_message', __('Error message'));
        $show->field('total_records', __('Total records'));
        $show->field('total_imported', __('Total imported'));
        $show->field('total_failed', __('Total failed'));
        //show district name 
        

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DataImport());
        $u = Admin::user();
        //get district 
        //$district = District::find($u->district_id);

        $form->hidden('user_id', __('User id'))->default($u->id);
        $form->text('title', __('Title'))->rules('required');
        //accepts only excel files
        $form->file('file', __('File'))
            ->rules('required|mimes:xlsx,xls')
            ->uniqueName();
        
        $form->select('district', __('District'))->options(District::orderBy('name', 'asc')->get()->pluck('name', 'id'));
     
        return $form;
    }
}
