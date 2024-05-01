<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Product;
use App\Models\Job;
use App\Models\Organisation;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Controllers\District_Union_Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

class DuDashboardController extends Controller
{
    public function index(Content $content)
    {
        $user = Admin::user();
        $organisation = Organisation::find($user->organisation_id);
        if (!$organisation) {
            Utils::check_default_organisation();
            $organisation = Organisation::find($user->organisation_id);
            if ($organisation == null) {
                die("Organisation not found");
            }
        }
        $content
            ->description('Hello, welcome to ' . $organisation->name . ' Dashboard');

        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $u = Admin::user();
                $organisation = Organisation::find($u->organisation_id);
                $district_id = $organisation->district_id;
                $count_pwd = Person::where('district_id', $district_id)->count();
                $box = new Box("Persons with Diability", '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . $count_pwd . '</h3>');
                $box->style('success')
                    ->solid();
                $column->append($box, view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Persons with Disability',
                    'sub_title' => 'pwds',
                    'number' => $count_pwd,
                    'font_size' => '1.5em',
                    'link' => admin_url('people'),
                ]));
            });
            $row->column(3, function (Column $column) {
                $u = Admin::user();
                $organisation = Organisation::find($u->organisation_id);
                $service_providers_count = ServiceProvider::with('districts_of_operation')
                    ->where('id', $organisation->district_id)
                    ->count();

                // Retrieve the total services count using the getDistrictServiceProviders() function
                $totalServices = District_Union_Dashboard::getDistrictServiceProviders()->getData()['totalServices'];

                $box = new Box(
                    "Service Providers",
                    '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' .
                        $totalServices . '</h3>'
                );
                $box->style('success')
                    ->solid();
                $column->append($box, view('widgets.box-5', [
                    'is_dark' => false,
                    'title' => 'Service Providers',
                    'font_size' => '1.5em',
                    'number' => $totalServices,
                    'link' => admin_url('service-providers'),
                ]));
            });


            $row->column(3, function (Column $column) {
                $box = new Box("Jobs",  '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . Job::count() . '</h3>');
                $box->style('success')
                    ->solid();
                $column->append(
                    $box,
                    view(
                        'widgets.box-5',
                        ['link' => admin_url('jobs'),]
                    )
                );
            });

            $row->column(3, function (Column $column) {
                $box = new Box("Products and Services",  '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . Product::count() . '</h3>');
                $box->style('success')
                    ->solid();
                $column->append(
                    $box,
                    ['link' => admin_url('products'),]
                );
            });
        });


        $content->row(function (Row $row) {
            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getGenderCountDisability());
            });
            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getDistrictByGenderAndAge());
            });
            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getDistrictDisabilityCount());
            });
        });

        $content->row(function (Row $row) {
            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getDistrictEducationByGender());
            });

            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getDistrictEmploymentStatus());
            });

            $row->column(4, function (Column $column) {
                $column->append(District_Union_Dashboard::getDistrictServiceProviders());
            });
        });

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
            ->description('Hello, welcome to ' . $organisation->name . ' Dashboard')
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
