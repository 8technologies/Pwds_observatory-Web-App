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
use Encore\Admin\Controllers\OPDDashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

class OPDDashboardController extends Controller
{
    public function index(Content $content)
    {
        $user = Admin::user();
        $organisation = Organisation::find($user->organisation_id);

        if (!($organisation && $organisation->relationship_type === 'opd')) {
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
                $u = Admin::user();
                $organisation = Organisation::find($u->organisation_id);
                if ($organisation && $organisation->relationship_type == "opd") {
                    $count_pwd = Person::where('opd_id', $organisation->id)->count();
                }
                $widgetData = [
                    'is_dark' => false,
                    'title' => 'Persons with Disability',
                    'sub_title' => 'pwds',
                    'number' => number_format($count_pwd), 
                    'font_size' => '1.5em',
                    'link' => admin_url("people?opd_id={$organisation->id}"),
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
                $totalServices = OPDDashboard::getOpdServiceProviders()->getData()['opdTotalServices'];
                $link = OPDDashboard::getOpdServiceProviders()->getData()['link'];

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

        $content->Row(function (Row $row) {
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getGenderCountDisability());
            });
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getOpdByGenderAndAge());
            });
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getOpdtDisabilityCount());
            });
        });
        $content->Row(function (Row $row) {
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getOpdEducationByGender());
            });
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getOpdEmploymentStatus());
            });
            $row->Column(4, function (Column $column) {
                $column->append(OPDDashboard::getOpdServiceProviders());
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
    }
}
