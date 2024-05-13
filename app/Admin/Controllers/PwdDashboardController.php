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
            $userGreeting = 'Hello ' . $user->first_name . '!';
        } elseif ($user && $admin_role == 'basic') {
            // For basic, use 'name'
            $userGreeting = 'Hello ' . $user->name . '!';
        } else {
            $userGreeting = 'Hello!';
        }

        // Set the content title and description.
        $content
            ->title($contentTitle)
            ->description($userGreeting);


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
