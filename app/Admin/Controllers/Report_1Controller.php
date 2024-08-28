<?php

namespace App\Admin\Controllers;

use App\Models\Report;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Barryvdh\DomPDF\Facade\Pdf;

class Report_1Controller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Report';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

     public function generatePdf($id)
     {
         $report = Report::findOrFail($id);
     
         // Load the view and pass the report data
         $pdf = PDF::loadView('admin.reports.report', compact('report'));
     
         // Return the generated PDF for download
         return $pdf->download('report_' . $report->id . '.pdf');
     }
     



    protected function grid()
    

    {
        $grid = new Grid(new Report());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('file_path', __('File path'))->hide();
        $grid->column('report_data', __('Report data'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        // Add a custom "Generate Report" button
    $grid->actions(function ($actions) {
        $actions->append('<a href="' . route('admin.reports.generate-pdf', ['id' => $actions->getKey()]) . '" class="btn btn-sm btn-primary">Generate PDF</a>');
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
        $show = new Show(Report::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('file_path', __('File path'))->hide();
        $show->field('report_data', __('Report data'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Report());

        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        //$form->text('file_path', __('File path'));
        $form->text('report_data', __('Report data'));
        $form->text('status', __('Status'))->default('pending');
        

        return $form;
    }
}
