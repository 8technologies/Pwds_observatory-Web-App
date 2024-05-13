<?php

namespace Encore\Admin\Controllers;

use App\Models\Disability;
use App\Models\District;
use App\Models\Event;
use App\Models\Job;
use App\Models\NewsPost;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Region;
use App\Models\ServiceProvider;
use Encore\Admin\Admin;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Type\NullType;

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

    public static function dashboard_jobs()
    {
        $jobs = Job::orderBy('created_at', 'desc')->paginate(10);
        return view('pwd-dashboard.job', ['jobs' => $jobs]);
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

    public static function getServiceProviderCount()
    {
        $service_providers = ServiceProvider::with('districts_of_operations', 'disability_categories')->get();
        $serviceCounts = [];
        $districtServiceCounts = [];

        foreach ($service_providers as $service_provider) {
            // Loop through each district associated with the service provider
            foreach ($service_provider->districts_of_operations as $district) {
                // Ensure $district is an object before attempting to access its properties
                if (is_object($district)) {
                    $districtName = $district->name ?? 'Unknown';

                    // Initialize the district in the districtServiceCounts array if it doesn't exist
                    if (!isset($districtServiceCounts[$districtName])) {
                        $districtServiceCounts[$districtName] = [];
                    }
                }

                // Loop through each disability category for the service provider
                foreach ($service_provider->disability_categories as $disability) {
                    // Ensure $disability is an object
                    if (is_object($disability)) {
                        // Initialize the disability category count per district
                        if (!isset($districtServiceCounts[$districtName][$disability->name])) {
                            $districtServiceCounts[$districtName][$disability->name] = 0;
                        }
                        $districtServiceCounts[$districtName][$disability->name]++;

                        // General count of service providers per disability category
                        if (!isset($serviceCounts[$disability->name])) {
                            $serviceCounts[$disability->name] = 0;
                        }
                        $serviceCounts[$disability->name]++;
                    }
                }
            }
        }

        // dd($serviceCounts, $districtServiceCounts);
        arsort($serviceCounts);
        arsort($districtServiceCounts);

        return view('dashboard.service_providers_per_disability', compact('serviceCounts', 'districtServiceCounts'));
    }

    //Method for retrieving person with disability by age
    public static function getDisabilityByGenderAndAge()
    {
        // Initialize the array to hold counts
        $disabilityCounts = [];

        // Get all people, eager loading their disabilities
        $people = Person::whereNotNull('sex')->where('sex', '<>', '')->get();

        foreach ($people as $person) {
            // Determine the person's age group
            $ageGroup = self::determineAgeGroup($person->age);

            if (is_null($ageGroup)) {
                continue;
            }

            // Skip if sex is null or empty
            if (is_null($person->sex) || $person->sex === '' || $person->sex === 'N/A') {
                continue;
            }

            if (!isset($disabilityCounts[$ageGroup][$person->sex])) {
                $disabilityCounts[$ageGroup][$person->sex] = 0;
            }

            // Increment the count for the current combination of age group and gender
            $disabilityCounts[$ageGroup][$person->sex]++;
        }

        return view('dashboard.genderCountByAgeGroup', compact('disabilityCounts'));
    }

    private static function determineAgeGroup($age)
    {
        if (is_null($age)) {
            return null;
        }
        if ($age <= 12) {
            return '0 - 12';
        } elseif ($age <= 18) {
            return '13 - 18';
        } elseif ($age <= 30) {
            return '19 - 30';
        } elseif ($age <= 45) {
            return '31 - 45';
        } elseif ($age <= 65) {
            return '46 - 65';
        } else {
            return '65+';
        }
    }

    public static function getEducationByGender()
    {
        // Define education levels
        $educ_levels = [
            'formal Education' => 'Formal Education',
            'informal Education' => 'Informal Education',
            'no Education' => 'No Education'
        ];

        // Fetch education data
        $educationData = Person::select('education_level', 'sex', DB::raw('count(*) as count'))
            ->whereNotNull('sex')
            ->where('sex', '<>', '')
            ->groupBy('education_level', 'sex')
            ->get()
            ->map(function ($item) use ($educ_levels) {
                // Map education level to its name
                $item->education_level = $educ_levels[$item->education_level] ?? 'Unknown';
                return $item;
            });

        // Separate education levels and genders
        $educationLevels = array_values($educationData->pluck('education_level')->unique()->toArray());
        $gender = $educationData->pluck('sex')->unique()->toArray();


        return view('dashboard.disability-education-gender', compact('educationLevels', 'gender', 'educationData'));
    }

    //Method for retrieving emploment status of people with disability according to my system
    public static function getEmploymentStatus()
    {
        // Define the allowed employment statuses
        $employmentStatus = [
            'formal employment' => 'Formal Employment',
            'self employment' => 'Self Employment',
            'unemployed' => 'Unemployed',
        ];

        // Fetch distinct employment statuses and gender from the Person model
        $employmentStatuses = Person::distinct('employment_status')
            ->whereIn('employment_status', $employmentStatus)
            ->pluck('employment_status')
            ->toArray();
        $gender = Person::distinct('sex')
            ->whereNotNull('sex')
            ->where('sex', '<>', 'N/A')
            ->pluck('sex')->toArray();

        $employment_status = Person::distinct()->pluck('employment_status');
        // Fetch employment status data with count
        $employmentStatusData = Person::select('employment_status', 'sex', DB::raw('count(*) as count'))
            ->groupBy('employment_status', 'sex')
            ->get()
            ->each(function ($item) use ($employmentStatus) {
                // Map each employment status to its name for display
                $item->employment_status = $employmentStatus[$item->employment_status] ?? 'Unknown';
            });

        return view('dashboard.employment_status_by_gender', compact('employmentStatuses', 'gender', 'employmentStatusData'));
    }


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
