<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Organisation;
use App\Models\ServiceProvider;
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


        $u = Admin::user();
        $admin_role = $u->roles->first()->slug;
        //if the user is not an admin, redirect to the guests page
        if ($u && $admin_role == 'basic') {
            return redirect()->route('pwd-dashboard');
        }
        if ($admin_role == 'district-union') {
            return redirect()->route('du-dashboard');
        }
        if ($u && $admin_role == 'pwd') {
            return redirect()->route('pwd-dashboard');
        }
        
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
        return $content; 

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
