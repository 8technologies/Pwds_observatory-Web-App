<?php

namespace App\Admin\Controllers;

use App\Models\Disability;
use App\Models\District;
use App\Models\Innovation;
use App\Models\Job;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Region;
use App\Models\Report;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Report';

    /**
     * Display a listing of the reports.
     *
     * @param Content $content
     * @return \Illuminate\View\View|\Encore\Admin\Layout\Content
     */
    public function index(Content $content)
    {
        // You can use this to debug whether this method is being hit
        // die('I am here');

        // Get the reports data (if needed)
       // $reports = Report::all();
       $usersCount = User::count();
       $disabilitiesCount = Disability::count();
       $jobsCount = Job::count();
       $personsCount = Person::count();
       $districtsCount = District::count();
       $regionsCount = Region::count();
       $innovationsCount = Innovation::count();
       $organisationsCount = Organisation::count();
       $data = [
           'title' => 'ObservatoryPwd Report',
           'content' => 'ObservatoryPwd Report',
           'usersCount' => $usersCount,
           'disabilitiesCount' => $disabilitiesCount,
           'jobsCount' => $jobsCount,
           'personsCount' => $personsCount,
           'districtsCount' => $districtsCount,
           'regionsCount' => $regionsCount,
           'innovationsCount' => $innovationsCount,
           'organisationsCount' => $organisationsCount
       ];
       
       // Load the view and render it
       $pdf = Pdf::loadView('admin.reports.report', $data);
       return $pdf->stream('8techReport.pdf');
        // For direct download:
        //return $pdf->download('8techReport.pdf');
        
        


        // Option 1: Render your custom view
        //return view('admin.reports.report' , $data);

        // Option 2: If you want to keep using the Content layout, you can do this:
        // return $content
        //     ->title($this->title)
        //     ->description('List of reports')
        //     ->view('admin.reports.index', compact('reports'));
    }
}
