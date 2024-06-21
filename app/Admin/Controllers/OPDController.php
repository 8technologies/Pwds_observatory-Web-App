<?php

namespace App\Admin\Controllers;

use App\Models\Organisation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\CreatedOPDMail;
use App\Admin\Extensions\OPDExcelExporter;
use App\Models\District;
use App\Models\Region;
use Encore\Admin\Facades\Admin;


class OPDController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'NOPDs';
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Organisation());
        $grid->disableBatchActions();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            //Filters for region, Membership type and date of registration
            $filter->like('name', 'Name');
            $filter->equal('membership_type', 'Membership Type')
                ->select(Organisation::pluck('membership_type', 'membership_type'));
            $filter->between('date_of_registration', 'Date of Registration')->date();
        });

        $user = auth("admin")->user();

        if ($user->inRoles(['district-union', 'opd', 'organisation'])) {

            $grid->disableCreateButton();
            $grid->disableActions();
        }

        /*         if ($user->isRole('district-union')) {
            //get the district union manager by the current user
            $district_union = Organisation::where('admin_email', $user->email)->first();

            $grid->model()->where('parent_organisation_id', $district_union->id)->where('relationship_type', 'opd')->orderBy('updated_at', 'desc');
        } elseif ($user->isRole('opd')) {
            $grid->model()->where('admin_email', $user->email)->orderBy('updated_at', 'desc');
        } elseif ($user->inRoles(['nudipu', 'administrator'])) {
        } */
        $grid->model()->where('relationship_type', 'opd')->orderBy('updated_at', 'desc');


        $grid->exporter(new OPDExcelExporter());
        $grid->column('name', __('Name'));
        $grid->column('registration_number', __('Registration number'));
        $grid->column('date_of_registration', __('Date of registration'));

        $grid->column('membership_type', __('Membership type'));
        $grid->column('physical_address', __('Physical address'));
        // $grid->column('contact_persons', __('Contact persons'));

        if (auth('admin')->user()->isRole('nudipu') || auth('admin')->user()->isRole('administrator')) {
            $grid->column('Reset Password')->display(function () {
                $url = url('du-admin-password-reset?du_id=' . $this->id);
                return "<a target='_blank' href='" . $url . "' class='btn btn-xs btn-primary'>Reset Password</a>";
            });
        }

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
        $show = new Show(Organisation::findOrFail($id));
        $model = Organisation::findOrFail($id);

        return view('admin.organisations.show', [
            'organisation' => $model
        ]);

        //Add new button to the top
        $show->panel()
            ->tools(function ($tools) use ($model) {
                $tools->disableList();
                $tools->disableDelete();
                if ($model->membership_type == 'member') {
                    $tools->append('<a class="btn btn-sm btn-primary mx-3" href="' . url('admin/opds/create') . '">Add NOPD</a>');
                    $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/district-unions/create') . '">Add District Union</a>');
                } else if ($model->membership_type == 'all') {
                    $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/people/create') . '">Add Person With Disability</a>');
                    $tools->append('<a class="btn btn-sm btn-primary mx-3" href="' . url('admin/opds/create') . '">Add NOPD</a>');
                    $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/district-unions/create') . '">Add District Union</a>');
                } else {
                    $tools->append('<a class="btn btn-sm btn-info mx-3" href="' . url('admin/people/create') . '">Add Person With Disability</a>');
                }
            });
        $show->field('name', __('Name'));
        $show->field('registration_number', __('Registration number'));
        $show->field('date_of_registration', __('Date of registration'));
        $show->field('mission', __('Mission'));
        $show->field('vision', __('Vision'));
        $show->field('core_values', __('Core values'));
        $show->field('brief_profile', __('Brief profile'));
        $show->field('membership_type', __('Membership type'));
        $show->field('physical_address', __('Physical address'));
        $show->field('website', __('Website'));
        $show->divider();
        $show->field('contact_persons', __('Contact persons'))->as(function ($contact_persons) {
            return $contact_persons->map(function ($contact_person) {
                return $contact_person->name . ' (' . $contact_person->position . ')' . ' - ' . $contact_person->phone1 . ' / ' . $contact_person->phone2;
            })->implode('<br>');
        });
        $show->divider();

        //     foreach($obj->attachments as $attachment){
        //         $show->field('attachments', __('Attachments'))->unescape()->as(function ($attachments) {
        //             return Arr::map($attachments,function ($attachment) {
        //                 return '<a href="'.$attachment->downloadable().'" target="_blank">'.$attachment->name.'</a>';
        //             })->implode('<br>');
        //         });
        //     }
        // //    $show->multipleFile($obj->attachments->downloadable();

        return $show;
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
            //$footer->disableEditingCheck();
            // $footer->disableCreatingCheck();
            // $footer->disableSubmit();
        });

        $form->divider('NOPD Basic Info');
        $form->text('name', __('NOPD Name'))->placeholder('Name')->rules("required");
        $form->text('registration_number', __('Registration number'))->placeholder('RegNo.');
        $form->date('date_of_registration', __('Date of registration'));
        $form->textarea('mission', __('Mission'))->placeholder('Org. Mission')->rules("required");
        $form->textarea('vision', __('Vision'))->placeholder('Org. Vision')->rules("required");
        $form->textarea('core_values', __('Core values'))->placeholder('Org. Core Values')->rules("required");
        $form->quill('brief_profile', __('Brief profile'))->placeholder('Org. Brief profile')->rules("required");
        $form->hidden('user_id')->default(Admin::user()->id);

        $form->divider('NOPD Membership');
        $form->radio('membership_type', __('Membership type'))->options(['organisation-based' => 'Organisation-based', 'individual-based' => 'Individual-based', 'both' => 'Both'])->rules("required");

        $form->divider('Contact');
        $form->text('physical_address', __('Physical address'))->placeholder('physical address')->rules("required");
        $form->text('website', __('Website'))->placeholder('http://www.example.com');

        $form->hasMany('contact_persons', 'Contact Persons', function (Form\NestedForm $form) {
            $form->text('name', __('Name'))->rules("required");
            $form->text('position', __('Position'))->rules("required");
            $form->email('email', __('Email'))->rules("required");
            $form->text('phone1', __('Phone Tel'))->rules("required");
            $form->text('phone2', __('Other Tel'));
        });

        $form->divider('Attachments');
        $form->file('logo', __('Logo'))->removable()->rules('mimes:png,jpg,jpeg')
            ->help("Upload image logo in png, jpg, jpeg format (max: 2MB)");
        $form->file('certificate_of_registration', __('Certificate of registration'))->removable()->rules('mimes:pdf')
            ->help("Upload certificate of registration in pdf format (max: 2MB)");
        $form->file('constitution', __('Constitution'))->removable()->rules('mimes:pdf')
            ->help("Upload certificate of registration in pdf format (max: 2MB)");

        $form->multipleFile('attachments', __('Other Attachments'))->removable()->rules('mimes:pdf,png,jpg,jpeg')
            ->help("Upload files such as certificate (pdf), logo (png, jpg, jpeg), constitution, etc (max: 2MB)");

        $form->divider('Districts of Operation');
        $form->multipleSelect('districtsOfOperation', __('Select Districts'))->options(District::all()->pluck('name', 'id'));

        $form->divider('Administrator');
        $form->text('admin_email');
        $form->hidden('parent_organisation_id')->default(0);
        $form->hidden('relationship_type')->default('opd');

        if ($form->isEditing()) {
            $form->radio('change_password', 'Change Password')->options(["No" => 'No', "Yes" => 'Yes'])
                ->when('Yes', function (Form $form) {
                    $form->password('password', __('New Password'));
                });
        } else {
            $form->password('password', __('Password'))->rules("required|min:6");
        }



        $form->ignore(['password', 'new_password', 'confirm_new_password', 'change_password']);


        return $form;
    }
}
