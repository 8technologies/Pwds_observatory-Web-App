<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\ServiceProvider;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

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

        $grid->column('id', __('Id'))->sortable()->hide();
        $grid->column('photo', __('Photo'))->image('', 50, 50);
        $grid->column('name', __('Name'))->sortable();
        $grid->column('type', __('Type'))->sortable()->display(function ($type) {
            $formattedType = ucfirst($type);
            return $formattedType;
        })->label([
            'product' => 'primary',
            'service' => 'danger'
        ]);
        $grid->column('service_provider_id', __('Service Provider'))->display(function ($service_provider_id) {
            $service_provider = ServiceProvider::find($service_provider_id);
            if ($service_provider) {
                return $service_provider->name;
            } else {
                return "N/A";
            }
        })->sortable();
        $grid->column('details', __('Details'))->display(function ($details) {
            return Str::words($details, 100, '...');
        });

        $grid->column('price', __('Price'));
        $grid->column('offer_type', __('Offer type'))->display(function ($offerType) {
            //Converting the first letter to caps
            $formattedOfferType = ucfirst($offerType);
            return $formattedOfferType;
        })->label([
            'sale' => 'success',
            'hire' => 'info',
            'free' => 'primary',
        ]);
        $grid->column('created_at', __('Created at'))
            ->display(function ($created_at) {
                return date('Y-m-d', strtotime($created_at));
            });
        $grid->column('updated_at', __('Updated at'))
            ->display(function ($updated_at) {
                return date('Y-m-d', strtotime($updated_at));
            })->sortable();

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

        $form->select('service_provider_id', __('Service Provider'))->options(ServiceProvider::orderBy('name', 'asc')->pluck('name', 'id'));
        return $form;
        $form->text('name', __('Name'))->rules("required");
        $form->radio('type', __('Type'))->options(['product' => 'Product', 'service' => 'Service'])
            ->when('product', function () {
            })
            ->when('service', function () {
            })->default('product')->rules("required");
        $form->image('photo', __('Photo'))->rules("required")->uniqueName();
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
