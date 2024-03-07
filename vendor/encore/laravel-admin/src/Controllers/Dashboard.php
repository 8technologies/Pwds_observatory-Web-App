<?php

namespace Encore\Admin\Controllers;

use App\Models\Disability;
use App\Models\District;
use App\Models\Event;
use App\Models\NewsPost;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Region;
use App\Models\ServiceProvider;
use Encore\Admin\Admin;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Dashboard
{

    public static function dashboard_members()
    {
        $members = Administrator::where([])->orderBy('id', 'desc')->limit(8)->get();
        return view('dashboard.members', [
            'items' => $members
        ]);
    }

    public static function dashboard_events()
    {
        $events = Event::where([])->orderBy('id', 'desc')->limit(8)->get();
        return view('dashboard.events', [
            'items' => $events
        ]);
    }

    public static function dashboard_news()
    {
        $events = NewsPost::where([])->orderBy('id', 'desc')->limit(8)->get();
        return view('dashboard.news', [
            'items' => $events
        ]);
    }

    public static function getDuOpdPerRegion()
    {
        $regions = Region::pluck('name')->toArray();
        $chartDataDU = Organisation::where('relationship_type', 'du')
            ->selectRaw('region_id, count(*) as count')
            ->groupBy('region_id')
            ->get();

        $chartDataOPD = Organisation::where('relationship_type', 'opd')
            ->selectRaw('region_id, count(*) as count')
            ->groupBy('region_id')
            ->get();
        return view('dashboard.du-nopd-chart', compact('regions', 'chartDataDU', 'chartDataOPD'));
    }

    public static function getMembershipChart()
    {
        $membershipTypes = Organisation::distinct('membership_type')->pluck('membership_type')->toArray();
        $membershipDataDU = Organisation::where('relationship_type', 'du')
            ->select('membership_type', DB::Raw('count(*) as count'))
            ->groupBy('membership_type')
            ->get();

        $membershipDataOPD = Organisation::where('relationship_type', 'opd')
            ->select('membership_type', DB::Raw('count(*) as count'))
            ->groupBy('membership_type')
            ->get();
        return view('dashboard.membership', compact('membershipTypes', 'membershipDataDU', 'membershipDataOPD'));
    }

    //Function for returning count of people with disability in a district grouped by sex
    public static function getPeopleWithDisability()
    {
        $sex = Person::whereNotNull('sex')->distinct('sex')->pluck('sex')->toArray();
        $barChart = Person::select('districts.name as district', DB::raw('count(*) as count'), 'people.sex as sex')
            ->join('districts', 'people.district_id', '=', 'districts.id')
            ->groupBy('districts.name', 'people.sex')
            ->whereNotNull('people.sex') // Eliminate data where 'sex' is null
            ->where('people.sex', '<>', 'N/A') // Eliminate data where 'sex' is 'N/A'
            ->get();

        return view('dashboard.gender-count', compact('sex', 'barChart'));
    }

    //PWDs Disability Category Count per district
    public static function getDisabilityCount()
    {
        $people = Person::with('disabilities', 'district')->get(); // Eager load people disabilities
        $disabilityCounts = [];
        $districtDisabilityCounts = [];

        foreach ($people as $person) {
            //District loaded has a name
            $districtName = $person->district->name ?? 'Unknown';

            // Initialize district in the array if not already present
            if (!array_key_exists($districtName, $districtDisabilityCounts)) {
                $districtDisabilityCounts[$districtName] = [];
            }
            foreach ($person->disabilities as $disability) {
                if (!isset($disabilityCounts[$disability->name])) {
                    $disabilityCounts[$disability->name] = 0;
                }
                $disabilityCounts[$disability->name]++;

                if (!isset($districtDisabilityCounts[$districtName][$disability->name])) {
                    $districtDisabilityCounts[$districtName][$disability->name] = 0;
                }
                $districtDisabilityCounts[$districtName][$disability->name]++;
            }
        }
        arsort($disabilityCounts);
        arsort($districtDisabilityCounts);

        return view('dashboard.disability-category-count', compact('disabilityCounts', 'districtDisabilityCounts'));
    }

    // public static function getServiceProviderCount()
    // {
    //     $service_providers = ServiceProvider::with('districts_of_operation', 'disability_category')->get(); // Eager load service providers
    //     $serviceCounts = [];
    //     $districtServiceCounts = [];

    //     foreach ($service_providers as $service_provider) {
    //         // District loaded has a name
    //         $districtName = $service_provider->districts_of_operation->name ?? 'Unknown';

    //         // Initialize district in the array if not already present
    //         if (!array_key_exists($districtName, $districtServiceCounts)) {
    //             $districtServiceCounts[$districtName] = [];
    //         }
    //         // Use the plural form when accessing the disability_category relationship
    //         foreach ($service_provider->disability_category as $disability) {
    //             // Ensure $disability is an object before attempting to access its properties
    //             if (is_object($disability)) {
    //                 if (!isset($serviceCounts[$disability->name])) {
    //                     $serviceCounts[$disability->name] = 0;
    //                 }
    //                 $serviceCounts[$disability->name]++;

    //                 if (!isset($districtServiceCounts[$districtName][$disability->name])) {
    //                     $districtServiceCounts[$districtName][$disability->name] = 0;
    //                 }
    //                 $districtServiceCounts[$districtName][$disability->name]++;
    //             }
    //         }
    //     }

    //     arsort($serviceCounts);
    //     arsort($districtServiceCounts);

    //     return view('dashboard.service_provider_per_disability', compact('serviceCounts', 'districtServiceCounts'));
    // }

    //Method for retrieving service providers residing in a particular district.
    public static function getTargetGroupByService()
    {
        // Define the allowed target groups
        $allowedTargetGroups = ['Children', 'Adults', 'Parents', 'Others'];

        // Fetch distinct target groups from the ServiceProvider model
        $targetGroup = ServiceProvider::distinct('target_group')
            ->whereIn('target_group', $allowedTargetGroups)
            ->pluck('target_group')
            ->toArray();

        // Fetch target group data with count
        $targetGroupData = ServiceProvider::select('target_group', DB::raw('count(*) as count'))
            ->whereIn('target_group', $allowedTargetGroups)
            ->groupBy('target_group')
            ->get();

        return view('dashboard.service-provider-per-targetgroup', compact('targetGroup', 'targetGroupData'));
    }

    public static function getServiceProviders($disability = null)
    {
        $availableDistricts = District::pluck('name')->toArray();
        $disabilityNames = Disability::pluck('name')->toArray();

        $serviceProviderCounts = [];

        foreach ($availableDistricts as $district) {
            foreach ($disabilityNames as $disability) {
                $count = ServiceProvider::where('districts_of_operation', 'LIKE', '%' . $district . '%')
                    ->where('disability_category', 'LIKE', '%' . $disability . '%')
                    ->count();

                $serviceProviderCounts[$district][$disability] = $count;
            }
        };

        arsort($serviceProviderCounts);

        return view('dashboard.service_providers_per_disability', compact('serviceProviderCounts', 'availableDistricts', 'disabilityNames'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function title()
    {
        return view('admin::dashboard.title');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP version',       'value' => 'PHP/' . PHP_VERSION],
            ['name' => 'Laravel version',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => 'Uname',             'value' => php_uname()],
            ['name' => 'Server',            'value' => Arr::get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => 'Cache driver',      'value' => config('cache.default')],
            ['name' => 'Session driver',    'value' => config('session.driver')],
            ['name' => 'Queue driver',      'value' => config('queue.default')],

            ['name' => 'Timezone',          'value' => config('app.timezone')],
            ['name' => 'Locale',            'value' => config('app.locale')],
            ['name' => 'Env',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function extensions()
    {
        $extensions = [
            'helpers' => [
                'name' => 'laravel-admin-ext/helpers',
                'link' => 'https://github.com/laravel-admin-extensions/helpers',
                'icon' => 'gears',
            ],
            'log-viewer' => [
                'name' => 'laravel-admin-ext/log-viewer',
                'link' => 'https://github.com/laravel-admin-extensions/log-viewer',
                'icon' => 'database',
            ],
            'backup' => [
                'name' => 'laravel-admin-ext/backup',
                'link' => 'https://github.com/laravel-admin-extensions/backup',
                'icon' => 'copy',
            ],
            'config' => [
                'name' => 'laravel-admin-ext/config',
                'link' => 'https://github.com/laravel-admin-extensions/config',
                'icon' => 'toggle-on',
            ],
            'api-tester' => [
                'name' => 'laravel-admin-ext/api-tester',
                'link' => 'https://github.com/laravel-admin-extensions/api-tester',
                'icon' => 'sliders',
            ],
            'media-manager' => [
                'name' => 'laravel-admin-ext/media-manager',
                'link' => 'https://github.com/laravel-admin-extensions/media-manager',
                'icon' => 'file',
            ],
            'scheduling' => [
                'name' => 'laravel-admin-ext/scheduling',
                'link' => 'https://github.com/laravel-admin-extensions/scheduling',
                'icon' => 'clock-o',
            ],
            'reporter' => [
                'name' => 'laravel-admin-ext/reporter',
                'link' => 'https://github.com/laravel-admin-extensions/reporter',
                'icon' => 'bug',
            ],
            'redis-manager' => [
                'name' => 'laravel-admin-ext/redis-manager',
                'link' => 'https://github.com/laravel-admin-extensions/redis-manager',
                'icon' => 'flask',
            ],
        ];

        foreach ($extensions as &$extension) {
            $name = explode('/', $extension['name']);
            $extension['installed'] = array_key_exists(end($name), Admin::$extensions);
        }

        return view('admin::dashboard.extensions', compact('extensions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function dependencies()
    {
        $json = file_get_contents(base_path('composer.json'));

        $dependencies = json_decode($json, true)['require'];

        return Admin::component('admin::dashboard.dependencies', compact('dependencies'));
    }
}
