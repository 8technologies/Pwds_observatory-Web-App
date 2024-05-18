<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Innovation;
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
                    'title' => 'Innovations',
                    'sub_title' => 'Innovation',
                    'number' => Innovation::count(),
                    'font_size' => '1.5em',
                    'link' => admin_url('innovations'),
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

            $row->column(3, function (Column $column) {
                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Service Providers',
                    'sub_title' => 'service providers',
                    'number' => ServiceProvider::count(),
                    'font_size' => '1.5em',
                    'link' => admin_url('service-providers'),
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
