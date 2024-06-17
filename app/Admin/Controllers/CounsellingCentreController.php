<?php

namespace App\Admin\Controllers;

use App\Models\CounsellingCentre;
use App\Models\Disability;
use App\Models\District;
use App\Models\Location;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class CounsellingCentreController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Counselling centres';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CounsellingCentre());



        $grid->filter(function ($f) {
            // Remove the default id filter
            $f->disableIdFilter();
            $f->between('created_at', 'Filter by registered')->date();
        });

        $grid->disableFilter();
        $grid->disableBatchActions();
        $grid->quickSearch('name')->placeholder('Search by name');
        $grid->model()->orderBy('id', 'desc');

        $grid->column('created_at', __('Regisetered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('name', __('Name'))->sortable();



        $grid->column('skills', __('Skills'));
        $grid->column('fees_range', __('Fees range'));



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
        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('website', __('Website'));

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
        $show = new Show(CounsellingCentre::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('administrator_id', __('Administrator id'));
        $show->field('disability_id', __('Disability id'));
        $show->field('name', __('Name'));
        $show->field('about', __('About'));
        $show->field('address', __('Address'));
        $show->field('parish', __('Parish'));
        $show->field('village', __('Village'));
        $show->field('phone_number', __('Phone number'));
        $show->field('email', __('Email'));
        $show->field('district_id', __('District id'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('website', __('Website'));
        $show->field('phone_number_2', __('Phone number 2'));
        $show->field('photo', __('Photo'));
        $show->field('gps_latitude', __('Gps latitude'));
        $show->field('gps_longitude', __('Gps longitude'));
        $show->field('status', __('Status'));
        $show->field('skills', __('Skills'));
        $show->field('fees_range', __('Fees range'));
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
        $form = new Form(new CounsellingCentre());


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
            $form->select('administrator_id', "Counselling Centre manager")
                ->options(function ($id) {
                    $a = Administrator::find($id);
                    if ($a) {
                        return [$a->id => "#" . $a->id . " - " . $a->name];
                    }
                })
                ->ajax($ajax_url)->rules('required');
        } else {
            $form->select('administrator_id', __('Counselling Centre manager'))
                ->options(Administrator::where('id', Auth::user()->id)->get()->pluck('name', 'id'))->default(Auth::user()->id)->readOnly()->rules('required');
        }


        $form->text('name', __('Counselling Centre Name'))->rules('required');

        $form->multipleSelect('disabilities', __('Select Category of persons with disabilities'))
            ->rules('required')
            ->options(Disability::where([])->get()->pluck('name', 'id'));

        $form->textarea('skills', __('Select counselling services offered'));
        $form->text('fees_range', __('Fees range'));

        $form->select('subcounty_id', __('Counselling Centre Subcounty'))
            ->help('Where is this Counselling Centre located?')
            ->options(Location::get_sub_counties_array());

        $form->text('village', __('Counselling Centre Village'));
        $form->text('parish', __('Counselling Centre Parish'));
        $form->text('address', __('Physical Address'));

        $form->text('phone_number', __('Counselling Centre Phone number'))->rules('required');
        $form->text('phone_number_2', __('Alternative Phone number'));
        $form->email('email', __('Counselling Centre Email address'));

        $form->url('website', __('Counselling Centre Website'));

        $form->text('gps_latitude', __('Counselling Centre Gps latitude'));
        $form->text('gps_longitude', __('Counselling Centre Gps longitude'));
        $form->image('photo', __('Counselling Centre logo'));
        $form->quill('about', __('About The Counselling Centre'))->rules('required');
        $form->multipleSelect('districts', 'Districts Covered')
            ->rules('required')
            ->options(District::where([])->get()->pluck('name', 'id'));

        $form->disableReset();
        $form->disableViewCheck();


        return $form;
    }
}
