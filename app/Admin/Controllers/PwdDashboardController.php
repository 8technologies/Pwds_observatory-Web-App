<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Auth;

class PwdDashboardController extends Controller
{

    public function checkApproval()
    {
        return view('approval');
    }


    public function index(Content $content)
    {
        $user = auth("admin")->user();
        $admin_role = $user->roles->first()->slug;

        if ($user && $admin_role == null) {
            return redirect()->route('approval');
        }

        $content
            ->title('ICT for Persons With Disabilities - Dashboard')
            ->description('Hello ' . $user->name . "!");


        $content->row(function (Row $row) {
            $row->column(6, function (Column $column) {
                $events = Dashboard::dashboard_events();
                $styledEvents = "<div style='padding: 20px; border: 1px solid #ccc; margin-bottom: 10px;'>{$events}</div>";
                $column->append($styledEvents);
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
