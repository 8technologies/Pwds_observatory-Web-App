<?php

namespace App\Admin\Controllers;

use App\Models\Disability;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Extensions\PersonsExcelExporter;
use App\Mail\NextOfKin as MailNextOfKin;
use App\Models\District;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PwdCreated;
use App\Models\NextOfKin;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Guest Page';


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        // $education_level = Dashboard::getEducationByGender();
        // dd($education_level);
        $form = new Form(new Person());

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
            $footer->disableSubmit();
        });

        $admin_roles = DB::table('admin_roles')
            ->whereIn('id', ['service-provider', 'pwd'])
            ->get();

        $form->select('role_id', 'Service Provider | PWD')
            ->options($admin_roles->pluck('role_id', 'id'))
            ->rules('required')
            ->helper('Select the role of the user');
        return $form;
    }
}
