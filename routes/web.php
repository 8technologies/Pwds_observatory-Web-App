<?php

use App\Admin\Controllers\ChatController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\Gen;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\DuDashboardController;
use App\Admin\Controllers\GuestController;
use App\Admin\Controllers\ImportPeopleController;
use App\Admin\Controllers\OPDDashboardController;
use App\Admin\Controllers\PersonController;
use App\Admin\Controllers\PwdDashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Middleware\Du_Dashboard;
use App\Mail\CreatedDistrictUnionMail;
use App\Models\Organisation;
use App\Models\Utils;
use Illuminate\Support\Facades\Mail;
use App\Admin\Controllers\Report_1Controller;
use App\Admin\Controllers\Report_2Controller;
use App\Http\Controllers\TemplateExportController;
use App\Http\Controllers\USSDController;
use Illuminate\Support\Facades\Artisan;

Route::match(['get', 'post'], '/ussd', [USSDController::class, 'index'])->name("ussd"); // USSD route

//migrate
Route::get('migrate', function () {
    Artisan::call('migrate');
    //SHOW migration result
    $output = Artisan::output();
    return "<pre>$output</pre>";
});

Route::get('du-admin-password-reset', function () {
    $du_id = $_GET['du_id'];
    $d  = Organisation::find($du_id);
    if ($d  == null) {
        die("District Union not found.");
    }
    try {
        $d->reset_admin_pass();
        echo "Password reset successfully.";
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
});
Route::get('mail-test', function () {

    /*   $admin_password = session('password') ?? '';
    Mail::to('mubahood360@gmail.com')->send(new CreatedDistrictUnionMail('Test Name', 'nankyaphio15@gmail.com', $admin_password));
    return 'Test email sent.'; */

    $name = 'Muhindo mubaraka';
    $body = <<<EOF
        Dear {$name},
        <br>
        <br>
        We are pleased to inform you that your account has been approved. You can now login to the PWD website and access all the features.
        <br>
        <br>
        Regards,
        <br>
        PWD Team.
    EOF;

    $data = [
        'email' => 'mubahood360@gmail.com',
        'name' => 'Phiona',
        'subject' => 'Test Mail',
        'body' => $body
    ];

    Utils::mail_send($data);
});


Route::get('generate-class', [MainController::class, 'generate_class']);
Route::get('generate-variables', [MainController::class, 'generate_variables']);
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/about-us', [MainController::class, 'about_us']);
Route::get('/our-team', [MainController::class, 'our_team']);
Route::get('/news-category/{id}', [MainController::class, 'news_category']);
Route::get('/news-category', [MainController::class, 'news_category']);
Route::get('/news', [MainController::class, 'news_category']);
Route::get('/news/{id}', [MainController::class, 'news']);
Route::get('/members', [MainController::class, 'members']);
Route::get('/dinner', [MainController::class, 'dinner']);
Route::get('/output', [MainController::class, 'output']);
Route::get('/testimonial', [MainController::class, 'testimonial']);
Route::get('/ucc', function () {
    return view('chair-person-message');
});
Route::get('/vision-mission', function () {
    return view('vision-mission');
});
Route::get('/constitution', function () {
    return view('constitution');
});
Route::get('/counseling-and-guidance', [MainController::class, 'counseling_centres']);
Route::get('counseling_search', [MainController::class, 'guidance_counseling_search'])->name('counseling_search');
Route::get('/register', [AccountController::class, 'register'])->name('register');


Route::get('service-providers', [MainController::class, 'service_providers']);
Route::get('service-providers/{id}', [MainController::class, 'service_provider']);
Route::get('disabilities', [MainController::class, 'disabilities']);
Route::get('disabilities/{id}', [MainController::class, 'disability']);
Route::get('innovations', [MainController::class, 'innovations']);
Route::get('innovations/{id}', [MainController::class, 'innovation']);
Route::get('jobs', [MainController::class, 'jobs']);
Route::get('jobs/{id}', [MainController::class, 'job']);
Route::get('job_search', [MainController::class, 'job_search'])->name('job_search');
Route::get('events', [MainController::class, 'events']);
Route::get('events/{id}', [MainController::class, 'event']);
Route::get('resources', [MainController::class, 'resources']);
Route::get('resources/{id}', [MainController::class, 'resource']);
Route::get('/du-dashboard', [DuDashboardController::class, 'index'])
    ->middleware('auth:admin')
    ->name('du-dashboard');
Route::get('/opd-dashboard', [OPDDashboardController::class, 'index'])
    ->middleware('auth:admin')
    ->name('opd-dashboard');
Route::get('/guest', [GuestController::class, 'index']);

Route::get('/login', [AccountController::class, 'login'])->name('login')
    ->middleware(RedirectIfAuthenticated::class);
Route::post('/account-activation', [AccountController::class, 'activateAccount'])->name('account-activation');
Route::get('activate', [AccountController::class, 'activate'])->name('activate');

Route::post('/register', [AccountController::class, 'register_post'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/login', [AccountController::class, 'login_post'])
    ->middleware(RedirectIfAuthenticated::class);


Route::get('/dashboard', [AccountController::class, 'dashboard'])
    ->middleware(Authenticate::class);

Route::middleware('auth:admin')->group(function () {
    Route::get('/approval', [PwdDashboardController::class, 'checkApproval'])->name('approval');
    Route::get('/pwd-dashboard', [PwdDashboardController::class, 'index'])
        ->name('pwd-dashboard');

    Route::middleware('verifyProfile')->group(function () {
        Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    });
});

//Route for DU dashboard
// Route::get('/du-dashboard', [DuDashboard::class, 'index'])->middleware(Du_Dashboard::class);


Route::get('/account-details', [AccountController::class, 'account_details'])
    ->middleware(Authenticate::class);

Route::post('/account-details', [AccountController::class, 'account_details_post'])
    ->middleware(Authenticate::class);

//forgot password
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::get('/logout', [AccountController::class, 'logout']);
Route::get('/gen', function () {
    die(Gen::find($_GET['id'])->do_get());
})->name("gen");



//Ogiki Moses Odera 
Route::get('admin/reports/generate-pdf/{id}', [Report_1Controller::class, 'generatePdf'])->name('admin.reports.generate-pdf');
Route::get('admin/reports/generate-pdf/{id}', [Report_2Controller::class, 'generatePdf'])->name('admin.reports.generate-pdf');

Route::get('/admin/import-people', [ImportPeopleController::class, 'showForm']);
Route::get('import-people-process', [ImportPeopleController::class, 'import_people_process']);
Route::post('/admin/import-people', [ImportPeopleController::class, 'import']);



// Route::get('data-import/template', function () {
//     return response()->download(public_path('templates/Pwd_Profiling_EightTech.xlsx'));
// })->name('data-import.template');

Route::get('data-import/template', [TemplateExportController::class, 'downloadTemplate'])->name('data-import.template');

Route::prefix(config('admin.route.prefix'))   // usually 'admin'
     ->middleware(config('admin.route.middleware'))  // usually ['web', 'admin']
     ->group(function () {
         Route::get('chat', [ChatController::class, 'index']);
     });

