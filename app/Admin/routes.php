<?php

use App\Admin\Controllers\AgentController;
use App\Admin\Controllers\ImportPeopleController;
use Illuminate\Routing\Router;
use App\Admin\Controllers\ReportController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('service-providers', ServiceProviderController::class);
    $router->resource('groups', GroupController::class);
    $router->resource('associations', AssociationController::class);
    $router->resource('people', PersonController::class);
    $router->resource('disabilities', DisabilityController::class);
    $router->resource('institutions', InstitutionController::class);
    $router->resource('counselling-centres', CounsellingCentreController::class);
    $router->resource('knowledge-management', KnowledgeManagementController::class);
    $router->resource('jobs', JobController::class);
    $router->resource('job-applications', JobApplicationController::class);

    $router->resource('course-categories', CourseCategoryController::class);
    $router->resource('courses', CourseController::class);
    $router->resource('settings', UserController::class);
    $router->resource('participants', ParticipantController::class);
    $router->resource('members', MembersController::class);
    $router->resource('post-categories', PostCategoryController::class);
    $router->resource('news-posts', NewsPostController::class);
    $router->resource('events', EventController::class);
    $router->resource('event-bookings', EventBookingController::class);
    $router->resource('products', ProductController::class);
    $router->resource('product-orders', ProductOrderController::class);

    $router->resource('organisations', OrganisationController::class);
    $router->resource('opds', OPDController::class);
    $router->resource('district-unions', DistrictUnionController::class);

    $router->resource('resources', ResourceController::class);
    $router->resource('innovations', InnovationController::class);
    $router->resource('testimonies', TestimonyController::class);

    $router->get('service-providers/{id}/verify', 'ServiceProviderController@verify')->name('service-providers.verify');
    $router->resource('gens', GenController::class);
    $router->resource('guest', GuestController::class);

    // Ogiki Moses Odera 
    
    $router->get('reports', [ReportController::class, 'index']);
    $router->resource('reports', ReportController::class)->except(['index']);

    // Second route for the reports
    
    $router->resource('reportspwd', Report_1Controller::class);

    $router->resource('reportspwd2', Report_2Controller::class);
    $router->resource('data-imports', DataImportController::class);

  
    $router->resource('du-agents', 'AgentController');


});
