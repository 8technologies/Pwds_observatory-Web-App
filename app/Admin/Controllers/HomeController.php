<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Association;
use App\Models\Group;
use App\Models\Location;
use App\Models\Person;
use App\Models\Product;
use App\Models\Job;
use App\Models\Organisation;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\Utils;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Faker\Factory as Faker;
use SplFileObject;

class HomeController extends Controller
{
    public function index(Content $content)
    {

        /*         
        Utils::importPwdsProfiles(Utils::docs_root().'/people-2.csv');  
        foreach (Person::all() as $key => $p) {
            if($p->employment_status == 'Yes'){
                $p->employment_status = 'Employed';
            }else{
                $p->employment_status = 'Not Employed';
            }
            $p->save();  
        } */
        /*  
        Utils::importPwdsProfiles(Utils::docs_root().'/people.csv');
        die();
          
  
        foreach (Administrator::all() as $key => $as) {
            $as->avatar = 'images/u-'.rand(1,10).'.png';
            $as->save();
        } */

        /*  

        $faker = Faker::create();
        $name = [
            'Gulu Women with Disabilities Union (GUWODU)',
            'Kijura Disabled Women Association (KIDWA)',
            'SignHealth Uganda (SU)',
            'Spinal Injuries Association Uganda (SIA-U)',
            'The National Association of the Deafblind in Uganda',
            'Jinja District Association of the Blind (JDAB)',
            'Busia District Union of People with Disabilities (BUDIPD)',
            'Kabale Association of People with Disabilities (KAPD)',
            'National Union of Disabled Persons of Uganda (NUDIPU)',
            'The Organisation for Emancipation of the Disabled',
            'United Deaf Women Organisation (UDEWO)',
            'Uganda Albinos Association',
            'Action for Youth with Disabilities Uganda (AYDU)',
            'Action on Disability& Development (ADD) International',
            'Masaka Disabled People Living with HIV/AIDS Association',
            'Uganda Parents with Deaf-Blind Association',
            'Comprehensive Rehabilitation Services in Uganda (CORSU)',
            'Uganda Persons with Disabilities Development Advocacy',
            'Youth and Persons with Disability (s) Integrated Development',
            'Uganda Landmine Survivors Association (ULSA)',
            'Sense International Uganda',
            'Masindi District People with Disability Union (MADIPHU)',
            'Save Children with Disabilities',
            'Youth with Physical Disability Development Forum',
            'Katalemwa Cheshire Home for Rehabilitation Services',
        ];
        $address = [
            'P.O Box 249, Gulu,Pawel Road, Opposite SOS children, Gulu',
            'P.O Box 36563, Kampala,Plot 99, Ntinda-Nakawa Road, Kampala, Kampala, Uganda',
            'P.O Box 1611 Wandegeya,Metal Health Uganda Office , Kampala',
            'P.O Box 379 Jinja ,JDAB offices, Mufubria subconty-Kumuli Road, Jinja',
            'P.O Box 124 Busia,District headquarters (District union office), Busia',
            'P.O Box 774 Kabale,District Headquarters near Education Department, Kabale',
        ];
        $subs = [];
        foreach (Location::get_sub_counties_array() as $key => $value) {
            $subs[] =  $key;
        }
     
        foreach ($name as $key => $value) {
            $as = new Association();
            shuffle($subs);
            shuffle($subs);
            shuffle($address);
            shuffle($address);
            shuffle($address);
            $as->administrator_id = 1;
            $as->name = $value;
            $as->members = rand(50,1000);
            $as->parish = 'Nyamambuka II';
            $as->status = 'Approved';
            $as->village = 'Bwera';
            $as->vision = 'Simple vision of this association';
            $as->mission = 'Simple mission of this association';
            $as->phone_number = '+256706638494';
            $as->phone_number_2 = '+256793204665';
            $as->email = 'test-maiil@gmail.com';
            $as->website = 'http://www.test-ste.com';
            $as->address = $address[2];
            $as->subcounty_id = $subs[15];
            $as->gps_latitude = '0.36532221688073396';
            $as->gps_longitude = '32.606444250275224';
            $as->photo = 'images/l-'.rand(1,10).'.png';
            $as->about = 'P.O Box 249, Gulu,Pawel Road, Opposite SOS children, Gulu The organization was founded by a group of disabled women. Initially, the group was called Makmatic. It was established to prevent discrimination, violence or abuse of women and girls with disabilities and empower them economically, socially and politically to have a dignified life. Vision Women and girls with disabilities able to unite, organize, manage and empowered to affirm their human rights and freedoms in a dignified mannerObjectives';

            $as->save();  
        } */

        /*  
        $ass = [];

        $groups = [];
        foreach (Group::all() as $key => $ass) {
            $groups[] = $ass->id;
        }


        foreach (Association::all() as $key => $ass) {

            $max = rand(2,10);
            for ($i = 1; $i < $max; $i++) {
                shuffle($address);
                shuffle($subs);
                shuffle($address);
                $c = new Group();
                $c->name = 'Group '.$i;
                $c->leader = 'M. Muhindo';
                $c->association_id = $ass->id;
                $c->address = $address[2];
                $c->parish = 'Test parish';
                $c->village = 'Test parish';
                $c->phone_number = '+256706638494';
                $c->phone_number_2 = '+256793204665';
                $c->email = 'muhindo@gmail.com';
                $c->subcounty_id = $subs[15]; 
                $c->members = rand(100,1000); 
                $c->started = Carbon::now(); 
                $c->save(); 
            }
        }

 
 

 


 


deleted_at
	 
*/

        /*         for ($i = 1; $i < 100; $i++) {
            shuffle($groups);
            shuffle($groups);
            shuffle($groups);
            shuffle($address);
            shuffle($subs);
            shuffle($subs);
            shuffle($subs);
            $c = new Person();
            $c->administrator_id = 1;
            $c->address = $address[2];
            $c->created_at = $faker->dateTimeBetween('-2 month', '+1 month');
            $c->dob = $faker->dateTimeBetween('-30 year', '-10 year');
            $c->group_id = $groups[2];
            $c->name = $faker->name();
            $c->caregiver_name = $faker->name();
            $c->parish = 'Kawanda';
            $c->village = 'Kansangati';
            $c->village = 'Kansangati';
            $c->phone_number = '+256706638494';
            $c->caregiver_phone_number = '+256706638494';
            $c->phone_number_2 = '+256793204665';
            $c->email = 'muhindo@gmail.com';
            $c->education_level = [
                'None',
                'Below primary',
                'Primary',
                'Secondary',
                'A-Level',
                'Bachelor',
                'Masters',
                'PhD',
            ][rand(0, 7)];
            $c->caregiver_relationship = [
                'Friend',
                'Brother',
                'Mother',
                'Father',
                'Sister',
                'Cousin',
                'Uncle',
                'Other',
            ][rand(0, 7)];
            $c->subcounty_id = $subs[15];
            $c->sex = ['Male', 'Female'][rand(0, 1)];
            $c->caregiver_sex = ['Male', 'Female'][rand(0, 1)];
            $c->has_caregiver = ['Yes', 'No'][rand(0, 1)];
            $c->caregiver_age = rand(10,50);
            $c->employment_status = ['Employed', 'Not Employed'][rand(0, 1)];
            $c->photo = 'images/u-'.rand(1,16).'.png';
            $c->save(); 
        }
 */

        $u = Admin::user();
        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Number Of DUs',
                    'sub_title' => 'dus',
                    'number' => Organisation::where('relationship_type', '=', 'du')->count(),
                    'link' => admin_url('district-unions'),
                    'font_size' => '1.5em'
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Number Of NOPDs',
                    'sub_title' => 'nopds',
                    'font_size' => '1.5em',
                    'number' => Organisation::where('relationship_type', '=', 'opd')->count(),
                    'link' => admin_url('opds'),
                ]));
            });
            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Service providers',
                    'sub_title' => 'service providers',
                    'number' => ServiceProvider::count(),
                    'link' => admin_url('service-providers'),
                ]));
            });

            $row->column(3, function (Column $column) {
                $column->append(view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Persons with Disability',
                    'sub_title' => 'pwds',
                    'number' => Person::count(),
                    'link' => admin_url('people'),
                ]));
            });
        });


        //Bar Chart for People with Disability count.
        $content->row(function (Row $row) {
            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getPeopleWithDisability());
            });

            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getDisabilityByGenderAndAge());
            });

            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getDisabilityCount());
            });
        });

        $content->row(function (Row $row) {
            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getEducationByGender());
            });

            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getDuOpdPerRegion());
            });

            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getMembershipChart());
            });
        });

        //Bar Chart for People with Service count.
        $content->row(function (Row $row) {
            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getEmploymentStatus());
            });
            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getServiceProviderCount());
            });

            $row->column(4, function (Column $column) {
                $column->append(Dashboard::getTargetGroupByService());
            });
        });


        // $content->row(function (Row $row) {
        //     $row->column(6, function (Column $column) {
        //         //group pesons with disabilities by categories
        //         $persons = DB::table('people')
        //             ->join('disability_person', 'people.id', '=', 'disability_person.person_id')
        //             ->join('disabilities', 'disability_person.disability_id', '=', 'disabilities.id')
        //             ->select('disabilities.name', DB::raw('COUNT(*) as count'))
        //             ->groupBy('disabilities.name')
        //             ->get();



        //         $column->append(view('widgets.by-categories', [
        //             'title' => 'Persons with Disabilities by Categories',
        //             'data' => $persons->pluck('count')->toArray(),
        //             'labels' =>  $persons->pluck('count', 'name')->map(function ($count, $name) {
        //                 return "$name - $count";
        //             })->values()->toArray()
        //         ]));
        //     });
        //     $row->column(6, function (Column $column) {
        //         //group pesons with disabilities by categories
        //         $persons = DB::table('people')
        //             ->select('place_of_birth', DB::raw('COUNT(*) as count'))
        //             ->groupBy('place_of_birth')
        //             ->get();

        //         // dd($persons);


        //         $column->append(view('widgets.by-place-of-birth', [
        //             'title' => 'Persons with Disabilities by Place Of Birth',
        //             'data' => $persons->pluck('count')->toArray(),
        //             'labels' =>  $persons->pluck('count', 'place_of_birth')->map(function ($count, $name) {
        //                 return "$name - $count";
        //             })->values()->toArray()
        //         ]));
        //     });
        // });


        $content
            ->title('ICT for Persons With Disabilities - Dashboard')
            ->description('Hello ' . $u->name . "!");

        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $column->append(Dashboard::dashboard_events());
            });
            $row->column(6, function (Column $column) {
                $column->append(Dashboard::dashboard_news());
            });
        });




        return $content;
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
