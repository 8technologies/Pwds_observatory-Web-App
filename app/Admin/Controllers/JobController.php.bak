<?php

namespace App\Admin\Controllers;

use App\Models\Job;
use App\Models\Location;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

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
   
        $grid->disableFilter();
        $grid->disableBatchActions();
        $grid->quickSearch('title')->placeholder('Search by Job Title');
        $grid->model()->orderBy('id', 'desc');

        $grid->column('created_at', __('Regisetered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable(); 
        $grid->column('title', __('Job Title'))->sortable();  
        $grid->column('details', __('Details'))->hide();
        $grid->column('nature_of_job', __('Nature of job'));
        $grid->column('minimum_academic_qualification', __('Minimum academic qualification'));
        $grid->column('required_expirience', __('Required expirience'));
        $grid->column('expirience_period', __('Expirience period (Years)'));
        $grid->column('category', __('Category'));  
        $grid->column('whatsapp', __('Whatsapp'));

        $grid->column('subcounty_id', __('Subcounty'))
            ->display(
                function ($x) {
                    $dis = Location::find($x);
                    if ($dis == null) {
                        return '-';
                    }
                    return $dis->name_text;
                }
            )->sortable(); 
 
        $grid->column('short_description', __('Short description'))->sortable();  

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
        $show->field('administrator_id', __('Administrator id'));
        $show->field('title', __('Title'));
        $show->field('short_description', __('Short description'));
        $show->field('details', __('Details'));
        $show->field('nature_of_job', __('Nature of job'));
        $show->field('minimum_academic_qualification', __('Minimum academic qualification'));
        $show->field('required_expirience', __('Required expirience'));
        $show->field('expirience_period', __('Expirience period'));
        $show->field('category', __('Category'));
        $show->field('photo', __('Photo'));
        $show->field('how_to_apply', __('How to apply'));
        $show->field('whatsapp', __('Whatsapp'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('district_id', __('District id'));

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
            $form->select('administrator_id', __('Job provider'))
                ->options(Administrator::where('id', Auth::user()->id)->get()->pluck('name', 'id'))->default(Auth::user()->id)->readOnly()->rules('required');
        }



        $form->text('title', __('Job Title'))->rules('required');

        $form->select('category', __('Industry'))->options([
            'Mass Communication' => 'Mass Communication',
            'Agriculture' => 'Agriculture',
            'Automotive' => 'Automotive',
            'Banking and Finance' => 'Banking and Finance',
            'Construction' => 'Construction',
            'Education' => 'Education',
            'Electricity' => 'Electricity',
            'Entertainment' => 'Entertainment',
            'Government' => 'Government',
            'Hospitality and Hotel' => 'Hospitality and Hotel',
            'Information Technology' => 'Information Technology',
            'Manufacturing and Warehousing' => 'Manufacturing and Warehousing',
            'NGO' => 'NGO',
            'Recruitment' => 'Recruitment',
            'Tourism and Travel' => 'Tourism and Travel',
        ])->rules('required');

        $form->select('subcounty_id', __('Job location'))
            ->rules('required')
            ->help('Where is this Counselling Centre located?')
            ->options(Location::get_sub_counties_array())->rules('required');

        $form->text('short_description', __('Short job description'))->rules('required');

        $form->radio('nature_of_job', __('Type of work'))->options([
            'Full time' => 'Full time',
            'Part time' => 'Part time',
            'Remote work' => 'Remote work',
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
        $form->text('required_expirience', __('Required expirience'))->rules('required');
        $form->decimal('expirience_period', __('Expirience period (in years)'))->rules('required');


        $form->date('deadline', __('Deadline (Available before)'))->rules('required');
        $form->decimal('slots', __('Slots available'))->rules('required');

        $form->image('photo', __('Photo'));
        $form->textarea('how_to_apply', __('How to apply'));
        $form->text('whatsapp', __('Whatsapp number'));



        $form->quill('details', __('Full job details'))->rules('required');


        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableReset();
        $form->disableViewCheck();

        return $form;
    }
}
