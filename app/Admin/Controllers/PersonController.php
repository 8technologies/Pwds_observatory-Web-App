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
use App\Models\District;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PwdCreated;
use Encore\Admin\Facades\Admin;

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


        $grid->filter(function ($f) {
            // Remove the default id filter
            $f->disableIdFilter();
            $f->between('created_at', 'Filter by registered')->date();


            // $f->equal('disabilities', 'Filter Type of disability')->select(
            //     Disability::where([])->orderBy('name', 'asc')->get()->pluck('name', 'id')
            // );
            $f->where(function ($query) {
                $query->whereHas('disabilities', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, 'Filter by Disability');

            $f->equal('district_id', 'Filter by district')->select(District::pluck('name', 'id'));




            // $district_ajax_url = url(
            //     '/api/ajax?'
            //         . "&search_by_1=name"
            //         . "&search_by_2=id"
            //         . "&query_parent=0"
            //         . "&model=Location"
            // );
            // $f->equal('district_id', 'Filter by district')->select(function ($id) {
            //     $a = Location::find($id);
            //     if ($a) {
            //         return [$a->id => "#" . $a->id . " - " . $a->name];
            //     }
            // })
            //     ->ajax($district_ajax_url);

            $f->equal('sex', 'Filter by Gender')->select([
                'Male' => 'Male',
                'Female' => 'Female',
            ]);


            $f->between('dob', 'Filter by date of birth range')->date();

            $f->equal('profiler', 'Filter by profiler Name')->select(
                Person::whereNotNull('profiler')->orderBy('profiler', 'asc')->pluck('profiler', 'profiler')
            );
        });

        $grid->quickSearch('name')->placeholder('Search by name');

        $user = auth("admin")->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        if ($user->inRoles(['nudipu', 'administrator'])) {
            $grid->model()->orderBy('id', 'desc');
        } elseif ($user->isRole('district-union')) {
            $grid->model()->where('district_id', $organisation->district_id)->orderBy('id', 'desc');
        } else if ($user->isRole('opd')) {
            $grid->model()->where('opd_id', $organisation->id)->orderBy('id', 'desc');
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
        $grid->column('is_formal_education', __('Formal Education'))->display(
            function ($x) {
                return $x ? 'Yes' : 'No';
            }
        )->sortable();

        $grid->column('district_id', __('Attached District'))->display(
            function ($x) {
                if ($this->district == null) {
                    return '-';
                }
                return $this->district->name;
            }
        )->sortable();

        $grid->column('profiler', __('Profiler'));

        $grid->column('disabilities', __('Disabilities'))
            ->display( 
                function ($x) {
                    //disabilities in badges
                    if ($this->disabilities()->count() > 0) {
                        $disabilities = $this->disabilities->map(function ($item) {
                            return "<span class='badge badge-success'>" . $item->name . "</span>";
                        })->toArray();
                        return join(' ', $disabilities);
                    }else {
                        return '-';  
                    }
                }
            );

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
        $show->field('ethnicity', __('Ethnicity'));
        $show->field('religion', __('Religion'));
        $show->field('marital_status', __('Marital status'));
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
        $form->tab('Bio', function ($form) {
            $form->image('photo', __('Photo'))->uniqueName();
            $form->text('name', __('Surname'))->rules('required');
            $form->text('other_names', __('Other Names'))->rules('required');
            $form->text('id_number', __('ID Number'))
                ->help("NIN, Passport Number, Driving Permit Number");
            $form->date('dob', __('Date of Birth'))->rules("required");
            $form->radio('sex', __('Gender'))->options(['Male' => 'Male', 'Female' => 'Female'])->rules('required');
            $form->radio('marital_status', __('Marital Status'))->options([
                'Single' => 'Single',
                'Married' => 'Married',
                'Divorced' => 'Divorced',
                'Widowed' => 'Widowed'
            ])->rules('required');
            $form->text('ethnicity', __('Ethnicity'))->rules('required')
                ->help('Your Tribe');
            $form->text('religion', __('Religion'))->rules('required');
            $form->select('district_of_origin', __('District Origin'))->options(District::pluck('name', 'id'))->rules("required");
            $form->radio('place_of_birth', __('Place Of Birth'))->options(['Hospital' => 'Hospital', 'Other' => 'Other'])
                ->when('Hospital', function ($form) {
                    $form->text('birth_hospital', __('Hospital Name'));
                })
                ->when('Other', function ($form) {
                    $form->textarea('birth_no_hospital_description', __('Description'))->placeholder('Where were you given birth to?')->rules("required");
                })
                ->rules("required");
            $form->text('languages', __('Languages'))->rules('required')
                ->help('English, Luganda, Runyakitara, etc');
            $form->multipleSelect('disabilities', __('Select disabilities'))
                ->rules('required')
                ->options(Disability::orderBy('name', 'asc')->get()->pluck('name', 'id'));
            $form->divider();

            $form->html('
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            ');
        });

        $form->tab('Academics', function ($form) {
            $form->radio('is_formal_education', __('Attended Formal Education'))->options([1 => 'Yes', 0 => 'No'])->rules('required')
                ->when(1, function (Form $form) {
                    $form->hasMany('academic_qualifications', "Start with highest Qualification", function (Form\NestedForm $form) {
                        $form->text('institution', __('Institution'));
                        $form->text('qualification', __('Qualification'));
                        $form->text('year_of_completion', __('Year Of Completion'));
                    })->default(0);
                });
            $form->divider();

            $form->html(' <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
 ');
        });

        $form->tab('Skills', function ($form) {
            $form->textarea('skills', __('Skills'))->rows(10)->placeholder("Enter skills forexample: knitting, dancing, teamwork, etc");
            $form->divider();

            $form->html(' <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
 ');
        });

        $form->tab('Employment', function ($form) {
            $form->radio('is_employed', __('Employment'))->options([1 => 'Yes', 0 => 'No'])->rules('required')
                ->when(1, function (Form $form) {
                    $form->text('employer', __('Current Employer Name'))->rules('required');
                    $form->text('position', __('Current Position'))->placeholder("Manager");
                    $form->text('year_of_employment', __('Current Period of service'))->placeholder("2022 - 2023");

                    $form->radio('is_formerly_employed', __('Employment History'))->options([1 => 'Yes', 0 => 'No'])
                        ->help("Are you formerly employed anywhere?")
                        ->default(0)
                        ->rules('required')
                        ->when(1, function (Form $form) {
                            $form->hasMany('employment_history', 'Previous Employment', function (Form\NestedForm $form) {
                                $form->text('employer', __('Employer Name'))->rules('required');
                                $form->text('position', __('Position'))->placeholder("Manager")->rules('required');
                                $form->text('year_of_employment', __('Period of service'))->placeholder("2022 - 2023")->rules('required');
                            })->default(0);
                        });
                })
                ->help("Are you currently employed? or have you ever been employed?");
            $form->divider();
            $form->html('
                <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            ');
        });
        $user = auth("admin")->user();

        if (!$user->inRoles(['district-union', 'opd'])) {
            $form->tab('Memberships', function ($form) {
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
                $form->divider();
                $form->html('
                    <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                    <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
                    ');
            });
        }

        $form->tab('Next of Kin', function ($form) {

            $form->hasMany('next_of_kins', ' Add New Next of Kin', function (Form\NestedForm $form) {
                $form->text('next_of_kin_last_name', __('Surname'))->rules('required');
                $form->text('next_of_kin_other_names', __('Other Names'))->rules('required');
                $form->radio('next_of_kin_gender', __('Gender'))->options(['Male' => 'Male', 'Female' => 'Female'])->rules('required');
                $form->mobile('next_of_kin_phone_number', __('Phone Number'))->rules('required');
                $form->mobile('next_of_kin_alternative_phone_number', __('Alternative Phone Number'));
                $form->email('next_of_kin_email', __('Email'));
                $form->text('next_of_kin_relationship', __('Relationship'))->rules('required');
                $form->text('next_of_kin_address', __('Address'))->rules('required');
            });
            $form->divider();
            $form->html('
            <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            ');
        });
        $form->tab('Aspirations', function ($form) {
            $form->quill('aspirations', __('Aspirations'));
            $form->divider();
            $form->html('
                <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
            ');
        });

        $form->tab('Address & Contacts', function ($form) {

            $form->radio('is_same_address', __('Is the same as next of kin?'))->options([1 => 'Yes', 0 => 'No'])->rules('required')
                ->when(0, function (Form $form) {
                    $form->text('address', __('Address'));
                    $form->mobile('phone_number', __('Phone Number'));
                    $form->mobile('phone_number_2', __('Other Phone Number'));
                    $form->text('email', __('Email'));
                })->default(0);
            $form->divider();
            $form->html('
                    <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
                    <a type="button" class="btn btn-primary btn-next float-right" data-toggle="tab" aria-expanded="true">Next</a>
                ');
        });
        if (Admin::user()->inRoles(['district-union', 'opd'])) {
            $form->tab('Profiler Name', function ($form) {
                $form->text('profiler', __('Profiler'))
                    ->placeholder('Enter your name as a profiler')
                    ->help('Enter your name as a profiler')
                    ->rules('required');

                if (Admin::user()->isRole('opd')) {
                    $current_user = auth("admin")->user();
                    $organisation = Organisation::where('user_id', $current_user->id)->first();
                    $form->select('district_id', __('Select Profiled District'))->options($organisation->districtsOfOperation->pluck('name', 'id'))->placeholder('Select Profiled District')->rules("required");
                }

                $form->divider();
                //Add submit button
                $form->html('
                <a type="button" class="btn btn-info btn-prev float-left" data-toggle="tab" aria-expanded="true">Previous</a>
    
                <button type="submit" class="btn btn-primary float-right">Submit</button>');
            });
        }
        $form->hidden('district_id');
        $form->hidden('opd_id');
        $form->hidden('is_approved');

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
                    $organisation = Organisation::where('user_id', $current_user->id)->first();
                    error_log("Organisation: " . $organisation->name);

                    if ($organisation == null) {
                        //return error
                        return back()->with('error', 'You do not have an organisation to register a member under');
                    } else if ($organisation->relationship_type == 'du') {
                        $form->district_id = $organisation->district_id;
                    } else if ($organisation->relationship_type == 'opd') {
                        $form->opd_id = $organisation->id;
                    }
                }
            });


            $form->saved(function (Form $form) {
                if ($form->isCreating()) {
                    $user_password = session('password');
                    error_log("Password: " . $user_password);
                    error_log("Email: " . $form->email);

                    if ($user_password != null) {

                        if ($form->email != null) {

                            Mail::to($form->email)->send(new PwdCreated($form->email, $user_password));
                        } else {
                            // Mail::to($form->pwd_email)->send(new NextOfKin("$form->name $form->other_names", $form->next_of_kin_email, $user_password));
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
