<?php

namespace App\Admin\Controllers;

use App\Models\Disability;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\PersonsExcelExporter;
use App\Mail\NextOfKin as MailNextOfKin;
use App\Models\District;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PwdCreated;
use App\Models\NextOfKin;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;

class PersonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Persons with disabilities';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Person());

        //TODO: fix filters, and also display users from the opd, and district unions


        $grid->filter(function ($filter) {
            // Remove the default id filter
            $filter->disableIdFilter();
            //filter by district, Gender, and disability type
            $filter->equal('district_id', 'Filter by district')
                ->select(District::orderBy('name', 'asc')->get()->pluck('name', 'id'));

            $filter->equal('sex', 'Filter by Gender')->select([
                'Male' => 'Male',
                'Female' => 'Female',
            ]);
            $filter->equal('disabilities.name', 'Disability Name')
                ->select(Disability::pluck('name', 'name'));
        });

        $grid->quickSearch('name')->placeholder('Search by name');

        $user = Admin::user();
        $organisation = Organisation::find(Admin::user()->organisation_id);
        if ($user->inRoles(['nudipu', 'administrator'])) {
            $grid->model()->orderBy('created_at', 'desc');
        } elseif ($user->isRole('district-union')) {
            $grid->model()->where('district_id', $organisation->district_id)->orderBy('created_at', 'desc');
        } else if ($user->isRole('opd')) {
            $grid->model()->where('opd_id', $organisation->id)->orderBy('created_at', 'desc');
        }
        //  else {
        //     // dd("ddd");
        //     $grid->model()->orderBy('id', 'desc');
        // }

        $grid->exporter(new PersonsExcelExporter());
        $grid->disableBatchActions();

        // $grid->column('id', __('Id'))->sortable();
        $grid->column('created_at', __('Registered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('other_names', __('Other Names'))->sortable();
        $grid->column('sex', __('Gender'))->sortable();
        $grid->column('education_level', __('Education'))->display(
            function ($education_level) {
                if ($education_level == 'formal Education' || $education_level == 1) {
                    return 'Formal Education';
                } else if ($education_level == 'informal Education') {
                    return "Informal Education";
                } else {
                    return "No Education";
                }
            }
        )
            ->sortable()->hide();
        $grid->column('employment_status', __('Employment Type'))
            ->display(function ($employee_status) {
                if ($employee_status == 'formal employment') {
                    return 'Formal Employment';
                } else if ($employee_status == 'self employment') {
                    return 'Self Employment';
                } else if ($employee_status == 'unemployed') {
                    return 'Unemployed';
                } else {
                    return 'Not mentioned';
                }
            })->sortable()->hide();;
        $grid->column('is_formal_education', __('Formal Education'))->display(
            function ($is_formal_education) {
                $levels = [
                    'PHD' => 'PHD',
                    'Masters' => 'Master\'s Degree',
                    'Bachelors' => 'Bachelor\'s Degree',
                    'Diploma' => 'Diploma',
                    'Secondary-UACE' => 'Secondary - UACE',
                    'Secondary-UCE' => 'Secondary - UCE',
                    'Primary' => 'Primary - PLE',
                ];
                if (array_key_exists($is_formal_education, $levels)) {
                    return $levels[$is_formal_education];
                } else {
                    return 'Not mentioned';
                }
            }
        )->sortable()->hide();
        $grid->column('informal_education', __('Informal Education'))->hide();

        $grid->column('district_id', __('Attached District'))->display(
            function ($x) {
                if ($this->district == null) {
                    return '-';
                }
                return $this->district->name;
            }
        )->sortable();

        $grid->column('profiler', __('Profiler'))->display(function ($profiler) {
            if (!$profiler) {
                return "Self Registered";
            } else {
                return $profiler;
            }
        });

        $grid->column('disabilities', __('Disabilities'))
            ->display(
                function ($x) {
                    //disabilities in badges
                    if ($this->disabilities()->count() > 0) {
                        $disabilities = $this->disabilities->map(function ($item) {
                            return  $item->name;
                        })->toArray();
                        return join(',', $disabilities);
                    } else {
                        return '-';
                    }
                }
            );

        $grid->column('is_approved', __('Approval'))->display(function ($x) {
            if ($x == 1) {
                return "<span class='badge badge-success'>Yes</span>";
            } else {
                return "<span class='badge badge-danger'>No</span>";
            }
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
        $persons = Person::findOrFail($id);
        $show = new Show($persons);

        return view('admin.persons.show',  [
            'pwd' => $persons
        ]);

        $show->photo()->image();
        $show->field('name', __('Name'));
        $show->field('other_names', __('Other names'));
        $show->field('id_number', __('Id number'));
        $show->field('dob', __('Dob'));
        $show->field('sex', __('Gender'));
        // $show->field('ethnicity', __('Ethnicity'));
        // $show->field('religion', __('Religion'));
        $show->field('place_of_birth', __('Place of birth'));
        $show->field('languages', __('Languages'));
        $show->field('address', __('Address'));

        $show->field('phone_number', __('Phone number'));
        $show->field('email', __('Email'));

        $show->field('next_of_kin_last_name', __('Next of kin last name'));
        $show->field('next_of_kin_other_names', __('Next of kin other names'));
        $show->field('next_of_kin_phone_number', __('Next of kin phone number'));
        $show->field('next_of_kin_id_number', __('Next of kin id number'));
        $show->field('next_of_kin_gender', __('Next of kin gender'));
        $show->field('next_of_kin_email', __('Next of kin email'));
        $show->field('next_of_kin_address', __('Next of kin address'));
        $show->field('next_of_kin_relationship', __('Next of kin relationship'));

        $show->field('skills', __('Skills'));
        $show->field('areas_of_interest', __('Areas of interest'));
        $show->field('aspirations', __('Aspirations'));

        $show->disabilities('Disabilities', function ($disabilities) use ($show) {
            $disabilities->resource('/admin/disabilities');
            // $disabilities->id();
            $disabilities->name();
            $disabilities->description()->limit(0);

            $disabilities->disableCreateButton();
            $disabilities->disableActions();
        });

        $show->affiliated_organisations('Memberships', function ($affiliated_organisations) {
            $affiliated_organisations->resource('/admin/affiliated-organisations');
            $affiliated_organisations->organisation_name();
            $affiliated_organisations->position();
            $affiliated_organisations->Year_of_membership();

            $affiliated_organisations->disableCreateButton();
            $affiliated_organisations->disableActions();
        });

        $show->academic_qualifications('Academic qualifications', function ($academic_qualifications) {
            $academic_qualifications->resource('/admin/academic-qualifications');
            $academic_qualifications->institution();
            $academic_qualifications->qualification();
            $academic_qualifications->year_of_completion();

            $academic_qualifications->disableCreateButton();
            $academic_qualifications->disableActions();
        });

        $show->employment_history('Employment history', function ($employment_history) {
            $employment_history->resource('/admin/employment-history');
            $employment_history->employer();
            $employment_history->position();
            $employment_history->year_of_employment();

            $employment_history->disableCreateButton();
            $employment_history->disableActions();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Person());

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
            $footer->disableSubmit();
        });
        $form->divider('Bio Data');
        $form->image('photo', __('Photo'))->uniqueName();
        $form->text('name', __('Surname'))->placeholder('Surname')->rules('required');
        $form->text('other_names', __('Other Names'))->placeholder('Other Names')->rules('required');
        $form->select('sex', __('Gender'))->options(['Male' => 'Male', 'Female' => 'Female'])->rules('required');
        $form->multipleSelect('disabilities', __('Select disabilities'))
            ->rules('required')
            ->options(Disability::orderBy('name', 'asc')->get()->pluck('name', 'id'));
        $form->date('dob', __('Date of Birth'))->format('DD-MM-YYYY')->placeholder('DD-MM-YYYY');
        $form->number('age', __('Age'))->placeholder('Age')->rules('required')->min(0);
        $form->mobile('phone_number', __('Phone Number'))->placeholder('Phone Number')->rules('required');
        $form->email('email', __('Email'))->placeholder('Email');
        $form->divider();
        $form->text('id_number', __('ID Number'))->placeholder('ID Number')
            ->help("NIN, Passport Number, Driving Permit Number");
        $form->select('district_of_origin', __('District of Origin'))->options(District::orderBy('name', 'asc')->get()->pluck('name', 'id'))->rules("required");



        $form->text('sub_county', __('Sub-County'))->placeholder('Enter Sub-County')->rules('required');
        $form->text('village', __('Village'))->placeholder('Enter village')->rules('required');
        //if age < 18, then marital status must be disabled
        $form->select('marital_status', __('Marital Status'))->options(
            ['Single' => 'Single', 'Married' => 'Married', 'Divorced' => 'Divorced', 'Widowed' => 'Widowed']
        )->rules('required');
        $form->text('ethnicity', __('Ethnicity'))->help('Your Tribe');
        $form->text('religion', __('Religion'));

        $form->divider('Academics');
        $form->select('education_level', __('Education'))->options(
            ['formal Education' => 'Formal Education', 'informal Education' => 'Informal Education', 'no Education' => 'No Education']
        )
            ->when('formal Education', function (Form $form) {
                $form->select('is_formal_education', __('Formal Education'))->options(['PHD' => 'PHD', 'Masters' => 'Masters', 'Bachelors' => 'Bachelors', 'Diploma' => 'Diploma', 'Secondary-UACE' => 'Secondary-UACE', 'Secondary-UCE' => 'Secondary-UCE', 'Primary' => 'Primary'])->rules('required')
                    ->when('PHD', function (Form $form) {
                        $form->text('indicate_class', 'Indicate class')->placeholder('Class');
                    })->when('Masters', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    })->when('Bachelors', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    })->when('Diploma', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    })->when('Secondary-UACE', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    })->when('Secondary-UCE', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    })->when('Primary', function (Form $form) {
                        $form->text('indicate_class', __('Indicate class'))->placeholder('Class');
                    });
            })->rules('required')
            ->when('informal Education', function (Form $form) {
                $form->text('informal_education', __('Informal Education'))->placeholder("Enter any informal education forexample: tailoring, carpentry, etc")->rules('required');
            })->rules('required')
            ->default('no Education');


        $form->divider('Skills');

        $form->textarea('skills', __('Skills'))->rows(10)->placeholder("Enter skills forexample: knitting, dancing, teamwork, etc");
        $form->divider();

        /*             $form->html(' <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>'); */


        $form->radio('is_employed', __('Are you Employed'))->options([1 => 'Yes', 2 => 'No'])->rules('required')
            ->when(1, function (Form $form) {
                $form->radio('employment_status', __('Indicate type of Employment'))->options(['formal employment' => 'Formal Employment', 'self employment' => 'Self Employment'])->rules('required')
                    ->when('formal employment', function (Form $form) {
                        $form->hasMany('employment_history', 'Previous Employment', function (Form\NestedForm $form) {
                            $form->text('employer', __('Employer Name'));
                            $form->text('position', __('Position'))->placeholder("Position");
                            $form->text('year_of_employment', __('Period of service'))->placeholder("2022 - 2023");
                        });
                    })
                    ->when('self employment', function (Form $form) {
                        $form->text('occupation', __('Occupation'))->placeholder('What is your occupation?')->rules('required')->help('e.g Farming, Fishing, Retailer');
                    })->default('formal employment');
            })->default(2)->required()
            ->help("Are you currently employed? or have you ever been employed?");
        $form->divider();
        // /*  $form->html('
        //         <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
        //         <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
        //     ');
        // });

        $user = auth("admin")->user();
        if (!$user->inRoles(['district-union', 'opd'])) {
            $form->divider('Memberships');
            $form->radio('is_member', __('Membership'))->options([1 => 'Yes', 0 => 'No'])->rules('required')
                ->when(1, function (Form $form) {
                    $form->radio('select_opd_or_du', __('Select '))->options(['opd' => 'OPD', 'du' => 'DU'])
                        ->help("Are you a member of an OPD or DU?")
                        ->when('du', function (Form $form) {
                            $form->select('district_id', __('Select  District'))->options(District::pluck('name', 'id'))->placeholder('Select District')->rules("required")
                                ->help("Select the District where your DU is located");
                        })
                        ->when('opd', function (Form $form) {

                            $form->select('opd_id', __('Select  OPD'))->options(Organisation::where('membership_type', 'individual-based')->where('relationship_type', 'opd')->pluck('name', 'id'))->placeholder('Select an OPD')->rules("required");
                        })
                        ->default('opd');
                    // $form->select('organisation_name', __('Select  DU / OPD'))->options(Organisation::where('membership_type','pwd')->pluck('name','id') )->placeholder('Select an Organisation')->rules("required");

                })
                ->help("Are you currently a member of any association? or have you ever been a member of any association?");
            /* $form->divider();
            $form->html('
                    <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                    <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
                    '); */
        }



        $form->hasMany('next_of_kins', ' Add New Next of Kin', function (Form\NestedForm $form) {
            $form->text('next_of_kin_last_name', __('Surname'))->rules('required');
            $form->text('next_of_kin_other_names', __('Other Names'))->rules('required');
            $form->radio('next_of_kin_gender', __('Gender'))->options(['Male' => 'Male', 'Female' => 'Female'])->rules('required');
            $form->text('next_of_kin_phone_number', __('Phone Number'))->rules('required');
            $form->text('next_of_kin_alternative_phone_number', __('Alternative Phone Number'));
            $form->email('next_of_kin_email', __('Email'));
            $form->text('next_of_kin_relationship', __('Relationship'))->rules('required');
            $form->text('next_of_kin_address', __('Address'))->rules('required');
        });
        /*  $form->divider();
        $form->html('
            <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            '); */
        $form->divider('Aspirations');
        $form->quill('aspirations', __('Aspirations'));



        if (Admin::user()->inRoles(['district-union', 'opd'])) {
            $form->tab('Profiler Name', function ($form) {
                $form->text('profiler', __('Profiler'))
                    ->placeholder('Enter your name as a profiler')
                    ->help('Enter your name as a profiler')
                    ->rules('required');


                if (Admin::user()->isRole('opd')) {
                    $current_user = auth("admin")->user();
                    $organisation = Organisation::find($current_user->organisation_id);
                    $form->select('district_id', __('Select Profiled District'))->options($organisation->districtsOfOperation->pluck('name', 'id'))->placeholder('Select Profiled District')->rules("required");
                }
                $form->divider();
                //Add submit button
                $form->html('
                        <button type="submit" class="btn btn-primary float-right">Submit</button>');
            });
        }

        // $form->divider();
        // //Add submit button
        // $form->html('
        //         <button type="submit" class="btn btn-primary float-right">Submit</button>');
        // $form->hidden('district_id');
        // $form->hidden('opd_id');
        // $form->hidden('is_approved');
        return $form;

        // Check if district union is doing the registration and send credentials else do not send
        if (auth("admin")->user()->inRoles(['district-union', 'opd'])) {
            $form->saving(function ($form) {
                // save the admin in users and map to this du
                if ($form->isCreating()) {
                    //generate random password for user and send it to the user's email
                    $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
                    $password = substr(str_shuffle($alpha_list), 0, 8);
                    //TODO: check if email was given, if not consider next_of_kin else no account
                    if ($form->email != null) {
                        $pwd_email = $form->email;
                        $new_password = $password;
                        $password = Hash::make($password);
                        //check if user exists
                        $admin = User::where('email', $pwd_email)->first();
                        if ($admin == null) {
                            $admin = User::create([
                                'username' => $pwd_email,
                                'email' => $form->pwd_email,
                                'password' => $password,
                                'first_name' => $form->other_names,
                                'last_name' => $form->name,
                                'gender' => $form->sex,
                                'avatar' => $form->photo
                            ]);
                            $admin->assignRole('pwd');
                        }
                        session(['password' => $new_password]);
                    } else {
                        //TODO: Send user has no email
                    }

                    $form->is_approved = 1; //Approve if registered by an organisation
                    $current_user = auth("admin")->user();
                    $organisation = Organisation::find($current_user->organisation_id);
                    error_log("Organisation: " . $organisation->name);

                    if ($organisation == null) {
                        //return error
                        return back()->with('error', 'You do not have an organisation to register a member under');
                    } else if ($organisation && $organisation->relationship_type == 'du') {
                        $form->district_id = $organisation->district_id;
                    } else if ($organisation && $organisation->relationship_type == 'opd') {
                        $form->opd_id = $current_user->organisation_id;
                    }
                }
            });
            //If user registers themselves, then information must be sent to du admin for approval
            $form->saved(function (Form $form) {
                if ($form->isCreating()) {
                    $user_password = session('password');
                    error_log("Password: " . $user_password);
                    error_log("Email: " . $form->email);


                    if ($user_password != null) {

                        if ($form->email != null) {
                            Mail::to($form->email)->send(new PwdCreated($form->email, $user_password));
                        } else {
                            Mail::to($form->pwd_email)->send(new MailNextOfKin("$form->name $form->other_names", $form->next_of_kin_email, $user_password));
                        }
                    }
                }
            });
        }


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
