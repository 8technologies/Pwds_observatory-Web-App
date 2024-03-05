<?php

namespace App\Admin\Controllers;

use App\Models\Association;
use App\Models\Disability;
use App\Models\Group;
use App\Models\Location;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class GroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Groups';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Group()); 
        $grid->disableFilter();
        $grid->disableBatchActions();
        $grid->quickSearch('name')->placeholder('Search by name');  
        $grid->model()->orderBy('id', 'desc'); 
         
        $grid->column('created_at', __('Regisetered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable(); 

        $grid->column('name', __('Name')); 
        $grid->column('association_id', __('Association'))
        ->display(
            function ($x) {
                $dis = Association::find($x);
                if ($dis == null) {
                    return '-';
                }
                return $dis->name;
            }
        )->sortable();

        $grid->column('started', __('Date Started'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable(); 
        $grid->column('leader', __('Group Leader'));  

        $grid->column('phone_number', __('Phone number'));
        $grid->column('email', __('Email'));
        $grid->column('members', __('Members')); 
  $grid->column('subcounty_id', __('subcounty'))
        ->display(
            function ($x) {
                $dis = Location::find($x);
                if ($dis == null) {
                    return '-';
                }
                return $dis->name_text;
            }
        )->sortable();  
        $grid->column('address', __('Address'));
        $grid->column('parish', __('Parish'));
        $grid->column('village', __('Village'));

        
        $grid->column('phone_number_2', __('Phone number 2'))->hide();

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
        $show = new Show(Group::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('association_id', __('Association id'));
        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
        $show->field('parish', __('Parish'));
        $show->field('village', __('Village'));
        $show->field('phone_number', __('Phone number'));
        $show->field('email', __('Email'));
        $show->field('district_id', __('District id'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('members', __('Members'));
        $show->field('phone_number_2', __('Phone number 2'));
        $show->field('started', __('Started'));
        $show->field('leader', __('Leader'));
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
        $form = new Form(new Group());

 

        if (
            (Auth::user()->isRole('staff')) ||
            (Auth::user()->isRole('admin'))
        ) {

            $ajax_url = url(
                '/api/ajax?'
                    . "search_by_1=name"
                    . "&search_by_2=id"
                    . "&model=Association"
            );
            $form->select('association_id', "Association")
                ->options(function ($id) {
                    $a = Association::find($id);
                    if ($a) {
                        return [$a->id => "#" . $a->id . " - " . $a->name];
                    }
                })
                ->ajax($ajax_url)->rules('required');
        } else {
            $form->select('association_id', __('Association'))
                ->options(Association::where('administrator_id', Auth::user()->id)->get()->pluck('name', 'id'))->rules('required');
        }


        $form->text('name', __('Group Name'))->rules('required');
        $form->text('leader', __('Group Leader Name'));
        $form->date('started', __('Started'));


        $form->multipleSelect('disabilities', __('Type of members\' disabilities'))
            ->rules('required')
            ->options(Disability::where([])->orderBy('name', 'asc')->get()->pluck('name', 'id'));


        $form->decimal('members', __('Number of Members'))->rules('required');

        $form->select('subcounty_id', __('Subcounty'))
            ->rules('required')
            ->help('Where is this business located?')
            ->options(Location::get_sub_counties_array());

        $form->text('village', __('Village'));
        $form->text('parish', __('Parish'));
        $form->text('address', __('Group Address'));
        $form->text('phone_number', __('Phone number'));
        $form->text('phone_number_2', __('Alternative Phone number'));
        $form->email('email', __('Email address'));
        $form->quill('details', __('Group details'));
 
        $form->disableReset();
        $form->disableViewCheck();


        return $form;
    }
}
