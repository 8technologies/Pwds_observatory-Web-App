<?php

namespace App\Admin\Controllers;

use App\Models\Association;
use App\Models\Location;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class AssociationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Association';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Association());

        $grid->quickSearch('name')->placeholder('Search by name');

        $grid->filter(function ($f) {
            // Remove the default id filter
            $f->disableIdFilter();
            $f->between('created_at', 'Filter by registered')->date(); 

            $district_ajax_url = url(
                '/api/ajax?'
                    . "&search_by_1=name"
                    . "&search_by_2=id"
                    . "&query_parent=0"
                    . "&model=Location"
            );
            $f->equal('district_id', 'Filter by district')->select(function ($id) {
                $a = Location::find($id);
                if ($a) {
                    return [$a->id => "#" . $a->id . " - " . $a->name];
                }
            })
                ->ajax($district_ajax_url);
        });

        $grid->model()->orderBy('id', 'desc');
        $grid->column('created_at', __('Regisetered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('name', __('Name'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('members', __('Members')); 
        $grid->column('district_id', __('District'))
            ->display(
                function ($x) {
                    $dis = Location::find($x);
                    if ($dis == null) {
                        return '-';
                    }
                    return $dis->name;
                }
            )->sortable();

        $grid->column('subcounty_id', __('Subcounty'))
            ->display(
                function ($x) {
                    $dis = Location::find($x);
                    if ($dis == null) {
                        return '-';
                    }
                    return $dis->name;
                }
            )->sortable(); 
        $grid->column('address', __('Address'));
        $grid->column('parish', __('Parish'));
        $grid->column('village', __('Village'));
        $grid->column('website', __('Website'))->hide();
        $grid->column('phone_number_2', __('Phone number 2'))->hide();
        $grid->column('vision', __('Vision'))->hide();
        $grid->column('mission', __('Mission')); 
        $grid->column('gps_latitude', __('Gps latitude'))->hide();
        $grid->column('gps_longitude', __('Gps longitude'))->hide(); 
        $grid->disableBatchActions();
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
        $show = new Show(Association::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('administrator_id', __('Administrator id'));
        $show->field('name', __('Name'));
        $show->field('about', __('About'));
        $show->field('address', __('Address'));
        $show->field('parish', __('Parish'));
        $show->field('village', __('Village'));
        $show->field('phone_number', __('Phone number'));
        $show->field('email', __('Email'));
        $show->field('district_id', __('District id'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('members', __('Members'));
        $show->field('website', __('Website'));
        $show->field('phone_number_2', __('Phone number 2'));
        $show->field('vision', __('Vision'));
        $show->field('mission', __('Mission'));
        $show->field('photo', __('Photo'));
        $show->field('gps_latitude', __('Gps latitude'));
        $show->field('gps_longitude', __('Gps longitude'));
        $show->field('status', __('Status'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Association());


        if (
            (Auth::user()->isRole('staff')) ||
            (Auth::user()->isRole('admin'))
        ) {

            $ajax_url = url(
                '/api/ajax?'
                    . "search_by_1=name"
                    . "&search_by_2=id"
                    . "&model=User"
            );
            $form->select('administrator_id', "Applicant")
                ->options(function ($id) {
                    $a = Administrator::find($id);
                    if ($a) {
                        return [$a->id => "#" . $a->id . " - " . $a->name];
                    }
                })
                ->ajax($ajax_url)->rules('required');
        } else {
            $form->select('administrator_id', __('Applicant'))
                ->options(Administrator::where('id', Auth::user()->id)->get()->pluck('name', 'id'))->default(Auth::user()->id)->readOnly()->rules('required');
        }


        $form->text('name', __('Association Name'))->rules('required');
        $form->date('start_date', __('Association Start Date'));
        $form->text('vision', __('Association Vision'));
        $form->text('mission', __('Association Mission'));

        $form->select('subcounty_id', __('Subcounty'))
            ->rules('required')
            ->help('Where is this association located?')
            ->options(Location::get_sub_counties_array());

        $form->text('village', __('Village'))->rules('required');
        $form->text('parish', __('Parish'))->rules('required');
        $form->text('address', __('Association Address'));

        $form->text('phone_number', __('Phone number'))->rules('required');
        $form->text('phone_number_2', __('Alternative Phone number'));
        $form->email('email', __('Email address'));

        $form->url('website', __('Website'));

        $form->text('gps_latitude', __('Association Gps latitude'));
        $form->text('gps_longitude', __('Association Gps longitude'));
        $form->image('photo', __('Association logo'));
        $form->quill('about', __('About this association'))->rules('required');

        $form->disableReset();
        $form->disableViewCheck();


        return $form;
    }
}
