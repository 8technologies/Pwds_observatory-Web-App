<?php

namespace App\Admin\Controllers;

use App\Mail\CreatedDistrictUnionMail;
use App\Models\Organisation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Admin\Extensions\DistrictUnionsExcelExporter;
use Encore\Admin\Admin;
use App\Models\District;
use App\Models\Region;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Exception;
use Illuminate\Support\Facades\Log;

class DistrictUnionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'District Unions';
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {



        $grid = new Grid(new Organisation());
        $grid->disableBatchActions();
        $grid->quickSearch('name')->placeholder('Search by Name');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            //Filters for region, membership type and date of registration
            $filter->equal('region.name', 'Region')
                ->select(Region::pluck('name', 'name'));
            $filter->equal('membership_type', 'Membership Type')
                ->select(Organisation::pluck('membership_type', 'membership_type'));
            $filter->between('date_of_registration', 'Date of Registration')->date();
        });


        $user = auth("admin")->user();

        if ($user->inRoles(['district-union', 'opd', 'organisation'])) {

            $grid->disableCreateButton();
            $grid->disableActions();
        }


        $grid->model()->where('relationship_type', 'du')->orderBy('created_at', 'desc');
        $grid->exporter(new DistrictUnionsExcelExporter());
        $grid->column('name', __('Name'))->sortable();
        $grid->column('registration_number', __('Registration number'));
        $grid->column('admin_email', __('Admin eMail'))
            ->sortable()
            ->filter('like');
        $grid->column('date_of_registration', __('Date of registration'));
        $grid->column('membership_type', __('Membership type'));
        $grid->column('physical_address', __('Physical address'));
        $grid->column('district_id', __('region'))->display(function ($district_id) {
            $region = Organisation::get_region($district_id);
            return $region;
        })->sortable();
        //created
        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            })->sortable();
        //reset admin password


        if (auth('admin')->user()->isRole('nudipu') || auth('admin')->user()->isRole('administrator')) {
            $grid->column('Reset Password')->display(function () {
                $url = url('du-admin-password-reset?du_id=' . $this->id);
                return "<a target='_blank' href='" . $url . "' class='btn btn-xs btn-primary'>Reset Password</a>";
            });
        }



          $grid->column('Send Message')->display(function () {
            // the org record has a user_id pointing at its admin user
            $currentAdminId = auth('admin')->id();

            // if it’s “you,” don’t render a button at all
            if ($this->user_id == $currentAdminId) {
                return ''; 
            }

            $chatUrl = admin_url('chat?receiver_id=' . $this->id);
            return "<a href='{$chatUrl}' class='btn btn-xs btn-success'>Send Message</a>";
        });
       

        // $grid->column('contact_persons', __('Contact persons'));

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
        // $show = new Show(Organisation::findOrFail($id));
        $model = Organisation::findOrFail($id);

        return view('admin.organisations.show', [
            'organisation' => $model
        ]);

        // session(['organisation_id' => $model->id]); //set a global organisation id

        // //Add new button to the top
        // $show->panel()
        //     ->tools(function ($tools) use ($model) {
        //         $tools->disableList();
        //         $tools->disableDelete();
        //         if ($model->membership_type == 'member') {
        //             $tools->append('<a class="btn btn-sm btn-primary mx-3" href="' . url('admin/opds/create') . '">Add OPD</a>');
        //         } else if ($model->membership_type == 'all') {
        //             $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/people/create') . '">Add Person With Disability</a>');
        //             $tools->append('<a class="btn btn-sm btn-primary mx-3" href="' . url('admin/opds/create') . '">Add OPD</a>');
        //         } else {
        //             $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/people/create') . '">Add Person With Disability</a>');
        //         }
        //     });
        // $obj = Organisation::find($id);

        // $show->field('name', __('Name'));
        // $show->field('registration_number', __('Registration number'));
        // $show->field('date_of_registration', __('Date of registration'));
        // $show->field('mission', __('Mission'));
        // $show->field('vision', __('Vision'));
        // $show->field('core_values', __('Core values'));
        // $show->field('brief_profile', __('Brief profile'));
        // $show->field('membership_type', __('Membership type'));
        // $show->field('physical_address', __('Physical address'));
        // $show->divider();
        // $show->field('contact_persons', __('Contact persons'))->as(function ($contact_persons) {
        //     return $contact_persons->map(function ($contact_person) {
        //         return $contact_person->name . ' (' . $contact_person->position . ')' . ' - ' . $contact_person->phone1 . ' / ' . $contact_person->phone2;
        //     })->implode('<br>');
        // });
        // $show->divider();

        // //     foreach($obj->attachments as $attachment){
        // //         $show->field('attachments', __('Attachments'))->unescape()->as(function ($attachments) {
        // //             return Arr::map($attachments,function ($attachment) {
        // //                 return '<a href="'.$attachment->downloadable().'" target="_blank">'.$attachment->name.'</a>';
        // //             })->implode('<br>');
        // //         });
        // //     }
        // // //    $show->multipleFile($obj->attachments->downloadable();

        // return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Organisation());
        /*  $d = Organisation::find(19);
        $d->name = "test".rand(10000, 99999);
        $d->relationship_type = "du";
        $d->district_id = 1;
        $d->save();
        die('done => ' . $d->id); */


        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            //$footer->disableEditingCheck();
            $footer->disableCreatingCheck();
            //$footer->disableSubmit();
        });
        /* 
        $u = Administrator::find(125);
        dd($u); */

        $form->divider('[Section 1 of 5] - DU Basic Information');
        $form->text('name', __('Name'))->rules("required");
        $form->select('district_id', __('District Of Operation'))->options(District::orderBy('name', 'asc')->pluck('name', 'id'))->rules("required");
        $form->text('registration_number', __('Registration number'));
        $form->date('date_of_registration', __('Date of registration'));
        $form->text('mission', __('Mission'));
        $form->text('vision', __('Vision'));
        $form->text('core_values', __('Core values'));
        $form->quill('brief_profile', __('Brief profile'));
        $form->divider('[Section 2 of 5] - DU Membership');
        $form->select('membership_type', __('Membership type'))->options(['organisation-based' => 'Organisation-based', 'individual-based' => 'Individual-based', 'both' => 'Both'])->rules("required");

        $form->divider('[Section 3 of 5] - DU Contact');

        $form->text('physical_address', __('Physical address'))->rules("required");
        $form->hasMany('contact_persons', 'Contact Persons', function (Form\NestedForm $form) {
            $form->text('name', __('Name'))->rules("required");
            $form->text('position', __('Position'))->rules("required");
            $form->email('email', __('Email'))->rules("required| email");
            $form->text('phone1', __('Phone Tel'))->rules("required|");
            $form->text('phone2', __('Other Tel'));
        });

        $form->divider('[Section 4 of 5] - DU Attachments');
        $form->file('logo', __('Logo'))->removable()->rules('mimes:png,jpg,jpeg')
            ->help("Upload image logo in png, jpg, jpeg format (max: 2MB)");
        $form->file('certificate_of_registration', __('Certificate of registration'))->removable()->rules('mimes:pdf')
            ->help("Upload certificate of registration in pdf format (max: 2MB)");
        $form->file('constitution', __('Constitution'))->removable()->rules('mimes:pdf')
            ->help("Upload constitution in pdf format (max: 2MB)");

        $form->multipleFile('attachments', __('Other Attachments'))->removable()->rules('mimes:pdf,png,jpg,jpeg')
            ->help("Upload files such as certificate (pdf), logo (png, jpg, jpeg), constitution, etc (max: 2MB)");

        $form->hidden('relationship_type')->default('du');
        $form->hidden('parent_organisation_id')->default(0);

        $form->divider('[Section 5 of 5] - DU Administrator');
        $form->text('admin_email', ('Administrator Email Address'))
            ->rules("required|email")
            ->help("This will be emailed with the password to log into the system");

        // if ($form->isEditing()) {
        //     $form->radio('change_password', 'Change Password')->options(["No" => 'No', "Yes" => 'Yes'])
        //         ->when('Yes', function (Form $form) {
        //             $form->password('password', __('New Password'));
        //             $form->password('confirm_new_password', __('Confirm Password'))->rules('same:new_password');
        //         });
        // } else {
        //     $form->password('password', __('Password'))->rules("required|min:6");
        //     $form->password('confirm_new_password', __('Confirm Password'))->rules('same:password');
        // }


        // $form->ignore(['password', 'new_password', 'confirm_new_password', 'change_password']);



        return $form;
    }
}
