<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        //display products by ID in descending order
        $grid->model()->orderBy('id', 'desc');
        $grid->disableRowSelector();

        $grid->column('id', __('Id'))->sortable();
        $grid->column('service_provider_id', __('Service provider id'));
        $grid->column('name', __('Name'));
        $grid->column('type', __('Type'));
        $grid->column('photo', __('Photo'));
        $grid->column('details', __('Details'));
        $grid->column('price', __('Price'));
        $grid->column('offer_type', __('Offer type'));
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('service_provider_id', __('Service provider id'));
        $show->field('name', __('Name'));
        $show->field('type', __('Type'));
        $show->field('photo', __('Photo'));
        $show->field('details', __('Details'));
        $show->field('price', __('Price'));
        $show->field('offer_type', __('Offer type'));
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
        $form = new Form(new Product());

        $form->hidden('service_provider_id');
        $form->text('name', __('Name'))->rules("required");
        $form->radio('type', __('Type'))->options(['product' => 'Product', 'service' => 'Service'])
            ->when('product', function () {
            })
            ->when('service', function () {
            })->default('product')->rules("required");
        $form->image('photo', __('Photo'))->rules("required");
        $form->radio('offer_type', __('Offer type'))->options(['free' => 'Free', 'hire' => 'Hire', 'sale' => 'Sale'])
            ->when('hire', function ($form) {
                $form->text('hire_description', __('Describe the rates'))->rules('required');
            })
            ->when('sale', function ($form) {
                $form->text('price', __('Price'))->rules('required|numeric|min:0');
            })
            ->default('sale');

        $form->quill('details', __('Details'));

        $form->saving(function (Form $form) {
            $admin = auth('admin')->user();
            //quill editor, eliminate html tags and keep only text
            $form->details = strip_tags($form->details);
            // $form->service_provider_id = auth('admin')->user()->service_provider->id;
        });




        return $form;
    }
}
