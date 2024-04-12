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
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Controllers\District_Union_Dashboard;
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
        $user = auth("admin")->user();
        $admin_role = $user->roles->first()->slug;

        if ($admin_role != 'pwd' && $admin_role != 'basic') {
            return redirect()->route('approval');
        } else {
            return redirect()->route('admin.people.create');
        }

        return $content
            ->title('ICT for Persons With Disabilities - Dashboard')
            ->description('Hello ' . $user->name . "!")
            ->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $person = Person::where('id', auth("admin")->user()->id)->first();
                    $column->append(Dashboard::dashboard_events());
                });
                $row->column(6, function (Column $column) {
                    $column->append(Dashboard::dashboard_news());
                });
            });
    }
}
