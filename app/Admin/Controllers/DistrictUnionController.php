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
use Encore\Admin\Controllers\Dashboard;
use Exception;

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

        if (!auth('admin')->user()->isRole('nudipu')) {
            $grid->disableCreateButton();
            $grid->disableActions();
        }
        $grid->model()->where('relationship_type', 'du')->orderBy('updated_at', 'desc');
        $grid->exporter(new DistrictUnionsExcelExporter());

        $grid->column('name', __('Name'))->sortable();
        $grid->column('registration_number', __('Registration number'));
        $grid->column('date_of_registration', __('Date of registration'));
        $grid->column('membership_type', __('Membership type'));
        $grid->column('physical_address', __('Physical address'));
        $grid->column('district_id', __('region'))->display(function ($district_id) {
            $region = Organisation::get_region($district_id);
            return $region;
        })->sortable();
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

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
            $footer->disableSubmit();
        });

        $form->tab('Info', function ($form) {
            $form->text('name', __('Name'))->rules("required");
            $form->select('district_id', __('District Of Operation'))->options(District::orderBy('name', 'asc')->pluck('name', 'id'))->rules("required");
            $form->text('registration_number', __('Registration number'));
            $form->date('date_of_registration', __('Date of registration'));
            $form->textarea('mission', __('Mission'));
            $form->textarea('vision', __('Vision'));
            $form->textarea('core_values', __('Core values'));
            $form->quill('brief_profile', __('Brief profile'));
            $form->divider();

            $form->html('
            <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>');
        });

        // $form->tab('Leadership', function ($form) {
        //     $form->hasMany('leaderships', function (Form\NestedForm $form) {
        //         $form->table('members', 'Members', function ($form) {
        //             $form->text('name', __('Name'));
        //             $form->text('position', __('Position'));
        //         });
        //         $form->date('term_of_office_start', __('Term of office start'));
        //         $form->date('term_of_office_end', __('Term of office end'));
        //     });
        // });

        $form->tab('Membership', function ($form) {
            $form->radio('membership_type', __('Membership type'))->options(['organisation-based' => 'Organisation-based', 'individual-based' => 'Individual-based', 'both' => 'Both'])->rules("required");
            $form->divider();

            $form->html('
            <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
            <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>');
        });

        $form->tab('Contact', function ($form) {
            $form->text('physical_address', __('Physical address'))->rules("required");


            $form->hasMany('contact_persons', 'Contact Persons', function (Form\NestedForm $form) {
                $form->text('name', __('Name'))->rules("required");
                $form->text('position', __('Position'))->rules("required");
                $form->email('email', __('Email'))->rules("required| email");
                $form->text('phone1', __('Phone Tel'))->rules("required|");
                $form->text('phone2', __('Other Tel'));
            });
            $form->divider();

            $form->html('
            <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
            <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
        ');
        });

        $form->tab('Attachments', function ($form) {
            $form->file('logo', __('Logo'))->removable()->rules('mimes:png,jpg,jpeg')
                ->help("Upload image logo in png, jpg, jpeg format (max: 2MB)");
            $form->file('certificate_of_registration', __('Certificate of registration'))->removable()->rules('mimes:pdf')
                ->help("Upload certificate of registration in pdf format (max: 2MB)");
            $form->file('constitution', __('Constitution'))->removable()->rules('mimes:pdf')
                ->help("Upload constitution in pdf format (max: 2MB)");

            $form->multipleFile('attachments', __('Other Attachments'))->removable()->rules('mimes:pdf,png,jpg,jpeg')
                ->help("Upload files such as certificate (pdf), logo (png, jpg, jpeg), constitution, etc (max: 2MB)");
            $form->divider();

            $form->html('
                <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            ');
        });
        // $form->tab('Membership Duration', function ($form) {
        //     $form->date('valid_from', __('Valid From'))->default(date('Y-m-d'));
        //     $form->date('valid_to', __('Valid To'))->default(date('Y-m-d'))->rules('after:start_date');
        //     $form->hidden('relationship_type')->default('du');
        //     $form->hidden('parent_organisation_id')->default(session('organisation_id'));

        //     $form->divider();

        //     $form->html('
        //         <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
        //         <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
        //     ');
        // });
        $form->tab('Administrator', function ($form) {
            $form->email('admin_email', ('Administrator'))->rules("required| email")
                ->help("This will be emailed with the password to log into the system");

            if ($form->isEditing()) {
                $form->divider('Change Password');
                $form->password('password', __('Old Password'))
                    ->help('Previous password');
                $form->password('new_password', __('New Password'));
                $form->password('confirm_new_password', __('Confirm Password'))->rules('same:new_password');
            }

            $form->divider();

            $form->html('
            <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
            <button type="submit" class="btn btn-primary float-right">Submit</button>        ');
        });
        $form->hidden('user_id')->default(0);
        $form->hidden('relationship_type')->default('du');
        $form->hidden('parent_organisation_id')->default(0);

        $form->submitted(function (Form $form) {
            if ($form->isEditing()) {
                $form->ignore(['admin_email', 'password', 'new_password', 'confirm_new_password']);
            }
        });

        $form->saving(function ($form) {

            // save the admin in users and map to this du
            if ($form->isCreating()) {
                $du_exists = Organisation::where('district_id', $form->district_id)->where('relationship_type', 'du')->exists();
                if ($du_exists) {
                    admin_error('District Union already exists', 'Please check the district and try again');
                    return back();
                }
                //generate random password for user and send it to the user's email
                $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
                $password = substr(str_shuffle($alpha_list), 0, 8);

                $admin_email = $form->admin_email;

                $new_password = $password;
                $password = Hash::make($password);
                //check if user exists
                $admin = User::where('email', $admin_email)->first();

                if ($admin == null) {
                    $admin = User::create([
                        'username' => $admin_email,
                        'email' => $form->admin_email,
                        'password' => $password,
                        'name' => $form->name,
                        'avatar' => $form->logo,
                    ]);

                    $admin->assignRole('district-union');
                }
                $form->user_id = $admin->id;
                $form->relationship_type = 'du';
                $form->parent_organisation_id = session('organisation_id');

                session(['password' => $new_password]);
            }
            if ($form->isEditing()) {

                $password = request()->input('password');
                $new_password = request()->input('new_password');
                $confirm_new_password = request()->input('confirm_new_password');

                if ($new_password != $confirm_new_password) {
                    admin_error('Passwords do not match', 'Please check the new password and try again');
                    return back();
                }

                // Check is password is not empty
                if ($password != null && $new_password != null) {
                    $administrator = $form->model()->administrator;

                    // check if old password is correct
                    if (Hash::check($password, $administrator->password, [
                        'rounds' => 12
                    ])) {
                        $administrator->password = Hash::make($new_password);
                        $administrator->save();

                        admin_success('Your password has been changed');
                    } else {
                        admin_error('Old password is incorrect', 'Please check the old password and try again');
                        return back();
                    }
                }
            }
        });



        $form->saved(function (Form $form) {
            if ($form->isCreating()) {
                $admin_password = session('password');

                Mail::to($form->admin_email)->send(new CreatedDistrictUnionMail($form->name, $form->admin_email, $admin_password));
            }
        });

        Admin::script(
            <<<EOT
            $(document).ready(function() {
                $('.btn-next').click(function() {
                    $('.nav-tabs > .active').next('li').find('a').trigger('click');
                });
                $('.btn-prev').click(function() {
                    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
                });
            });
            EOT
        );

        return $form;
    }
}
