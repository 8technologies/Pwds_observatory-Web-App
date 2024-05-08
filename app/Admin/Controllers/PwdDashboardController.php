<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Product;
use App\Models\ServiceProvider;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Auth;

class PwdDashboardController extends Controller
{

    public function checkApproval()
    {
        return view('approval');
    }


    public function index(Content $content)
    {
        $user = Admin::user();
        $admin_role = $user->roles->first()->slug;

        if ($user && $admin_role == null) {
            return redirect()->route('approval');
        }

        $contentTitle = 'ICT for Persons With Disabilities - Dashboard';
        $userGreeting = '';

        // Check the user role 
        if ($user && $admin_role == 'pwd') {
            // For 'pwd', use 'first_name'
            $userGreeting = 'Hello ' . $user->first_name . '!';
        } elseif ($user && $admin_role == 'basic') {
            // For basic, use 'name'
            $userGreeting = 'Hello ' . $user->name . '!';
        } else {
            // Default greeting 
            $userGreeting = 'Hello!';
        }

        // Set the content title and description.
        $content
            ->title($contentTitle)
            ->description($userGreeting);

        // $content
        //     ->title('ICT for Persons With Disabilities - Dashboard')
        //     ->description('Hello ' . $user->first_name . "!");

        // $content->row(function (Row $row) {
        //     $row->column(3, function (Column $column) {
        //         $u = Admin::user();
        //         $organisation = Person::find($u->district_id);
        //         $district_id = $organisation->district_id;
        //         $count_pwd = Person::where('district_id', $district_id)->count();
        //         $box = new Box("Persons with Diability", '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . $count_pwd . '</h3>');
        //         $box->style('success')
        //             ->solid();
        //         $column->append($box, view('widgets.box-5', [
        //             'is_dark' => false,
        //             'title' => 'Persons with Disability',
        //             'number' => $count_pwd,
        //             'font_size' => '1.5em'
        //         ]));
        //     });
        //     $row->column(3, function (Column $column) {
        //         $u = Admin::user();
        //         $organisation = Organisation::find($u->organisation_id);
        //         $service_providers = ServiceProvider::with('districts_of_operation')->where('id', $organisation->district_id)->count();
        //         $box = new Box("Service Providers",  '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . $service_providers . '</h3>');
        //         $box->style('success')
        //             ->solid();
        //         $column->append($box, view('widgets.box-5', [
        //             'is_dark' => false,
        //             'title' => 'Service Providers',
        //             'sub_title' => 'service providers',
        //             'font_size' => '1.5em',
        //             'number' => $service_providers,
        //             'link' => admin_url(),
        //         ]));
        //     });
        //     $row->column(3, function (Column $column) {
        //         $box = new Box("Jobs",  '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . Job::count() . '</h3>');
        //         $box->style('success')
        //             ->solid();
        //         $column->append($box);
        //     });

        //     $row->column(3, function (Column $column) {
        //         $box = new Box("Products and Services",  '<h3 style="text-align:center; margin:0; font-size:40px; font-weight: bold;">' . Product::count() . '</h3>');
        //         $box->style('success')
        //             ->solid();
        //         $column->append($box);
        //     });
        // });


        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $events = Dashboard::dashboard_events();
                $column->append($events);
            });

            $row->column(6, function (Column $column) {
                $column->append(Dashboard::dashboard_news());
            });
        });

        $content->row(function (Row $row) {
            $row->column(12, function (Column $column) {
                $column->append(Dashboard::dashboard_jobs());
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
