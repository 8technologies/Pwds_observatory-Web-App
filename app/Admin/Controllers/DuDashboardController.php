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
                return redirect()->route('login')->with('error', 'Organisation not found');
            }
        }
        $content
            ->description('Hello, welcome to ' . $organisation->name . ' Dashboard');

        $content->row(function (Row $row) {
            $row->column(3, function (Column $column) {
                $user = Admin::user();
                $organisation = Organisation::find($user->organisation_id);
                $district_id = $organisation->district_id;
                $count_pwd = Person::where('district_id', $district_id)->count();

                // Prepare data for the view
                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Persons with Disability',
                    'sub_title' => 'pwds',
                    'number' => number_format($count_pwd),
                    'font_size' => '1.5em',
                    'link' => admin_url("people?district_id={$district_id}"),
                ];

                $box_content = "<h3 style='text-align:center; margin:0; font-size:40px; font-weight: bold;'>{$widgetData['number']}</h3> 
                                <p>{$widgetData['sub_title']}</p>";
                $box_content .= '<a href="' . $widgetData['link'] . '" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>';

                $box = new Box($widgetData['title'], $box_content);
                $box->style('success')
                    ->solid();
                $column->append($box);
            });
            $row->column(3, function (Column $column) {
                // Retrieve the total services count using the getDistrictServiceProviders() function
                $totalServices = District_Union_Dashboard::getDistrictServiceProviders()['totalServices'];
                $link = District_Union_Dashboard::getDistrictServiceProviders()->getData()['link'];

                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Service Providers',
                    'sub_title' => 'service providers',
                    'number' => $totalServices,
                    'font_size' => '1.5em',
                    'link' => admin_url($link),
                ];

                $box_content = "<h3 style='text-align:center; margin:0; font-size:40px; font-weight: bold;'>{$widgetData['number']}</h3> 
                <p>{$widgetData['sub_title']}</p>";
                $box_content .= '<a href="' . $widgetData['link'] . '" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>';

                $box = new Box($widgetData['title'], $box_content);
                $box->style('success')
                    ->solid();
                $column->append($box);
            });


            $row->column(3, function (Column $column) {

                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Available jobs',
                    'sub_title' => 'jobs',
                    'number' => Job::count(),
                    'font_size' => '1.5em',
                    'link' => admin_url('jobs'),
                ];

                $box_content = "<h3 style='text-align:center; margin:0; font-size:40px; font-weight: bold;'>{$widgetData['number']}</h3> 
                <p>{$widgetData['sub_title']}</p>";
                $box_content .= '<a href="' . $widgetData['link'] . '" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>';

                $box = new Box($widgetData['title'], $box_content);
                $box->style('success')
                    ->solid();
                $column->append($box);
            });

            $row->column(3, function (Column $column) {
                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Products',
                    'sub_title' => 'products',
                    'number' => Product::count(),
                    'font_size' => '1.5em',
                    'link' => admin_url('products'),
                ];

                $box_content = "<h3 style='text-align:center; margin:0; font-size:40px; font-weight: bold;'>{$widgetData['number']}</h3> 
                <p>{$widgetData['sub_title']}</p>";
                $box_content .= '<a href="' . $widgetData['link'] . '" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></a>';

                $box = new Box($widgetData['title'], $box_content);
                $box->style('success')
                    ->solid();
                $column->append($box);
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

        // $content->row(function (Row $row) {
        //     $row->column(6, function (Column $column) {
        //         $column->append(Dashboard::dashboard_events());
        //     });
        //     $row->column(6, function (Column $column) {
        //         $column->append(Dashboard::dashboard_news());
        //     });
        // });
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
