<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 300);
ini_set('max_input_time', 300);
ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('max_file_uploads', '100');
ini_set('default_socket_timeout', 300);
ini_set('max_input_vars', 10000);
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.cookie_lifetime', 86400);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

use App\Models\Utils;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Auth;
use App\Admin\Extensions\Nav\Shortcut;
use App\Admin\Extensions\Nav\Dropdown;
use App\Models\Organisation;

//inclide css blade view
Utils::system_boot();


// Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {

//     $u = Auth::user();
//     $navbar->left(view('admin.search-bar', [
//         'u' => $u
//     ]));

//     $navbar->left(Shortcut::make([
//         'News post' => 'news-posts/create',
//         'Products or Services' => 'products/create',
//         'Jobs and Opportunities' => 'jobs/create',
//         'Event' => 'events/create',
//     ], 'fa-plus')->title('ADD NEW'));
//     $navbar->left(Shortcut::make([
//         'Person with disability' => 'people/create',
//         'Association' => 'associations/create',
//         'Group' => 'groups/create',
//         'Service provider' => 'service-providers/create',
//         'Institution' => 'institutions/create',
//         'Counselling Centre' => 'counselling-centres/create',
//     ], 'fa-wpforms')->title('Register new'));

//     $navbar->left(new Dropdown());

//     $navbar->right(Shortcut::make([
//         'How to update your profile' => '',
//         'How to register a new person with disability' => '',
//         'How to register as service provider' => '',
//         'How to register to post a products & services' => '',
//         'How to register to apply for a job' => '',
//         'How to register to use mobile App' => '',
//         'How to register to contact us' => '',
//         'How to register to give a testimonial' => '',
//         'How to register to contact counselors' => '',
//     ], 'fa-question')->title('HELP'));
// });

/* $org = new Organisation();
$org->name = 'Test D.U';
$org->district_id = 41;
$org->registration_number = 'Test D.U';
$org->date_of_registration = 'Test D.U';
$org->mission = 'Test D.U';
$org->membership_type = 'organisation-based';
$org->physical_address = 'organisation-based';
$org->core_values = 'organisation-based';
$org->brief_profile = 'organisation-based';
$org->vision = 'organisation-based';
$org->relationship_type = 'du';
$org->parent_organisation_id = '0';
$org->admin_email = 'mubs0x@gmal.com'; 
$org->save();
die("doen");
 */
Encore\Admin\Form::forget(['map', 'editor']);
Admin::css(url('/assets/css/bootstrap.css'));
Admin::css('/assets/css/styles.css');
