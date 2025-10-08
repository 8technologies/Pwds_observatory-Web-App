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
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Type\NullType;

class OPDDashboard
{


    //Function for returning count of people with disability in a district grouped by sex
    public static function getGenderCountDisability()
    {
        // Get the district union ID of the logged-in user
        $u = auth()->user();
        $organisation = Organisation::find($u->organisation_id);
        if ($organisation && $organisation->relationship_type == 'opd') {
            $opdId = $organisation->id;
            $opdName = $organisation->name;
            $gender = Person::WhereNotNull('sex')->distinct('sex')->pluck('sex')->toArray();

            $genderCount = Person::select('organisations.name as opd', DB::raw('count(*) as count'), 'people.sex as sex')
                ->join('organisations', 'people.opd_id', '=', 'organisations.id')
                ->where('organisations.id', $opdId)
                ->groupby('organisations.name', 'people.sex')
                ->whereNotNull('people.sex')
                ->where('people.sex', '<>', 'N/A')
                ->get();
        }

        return view('opd-dashboard.disability-gender-count', compact('gender', 'genderCount', 'opdName'));
    }

    //PWDs Disability Category Count per district
    public static function getOpdByGenderAndAge()
    {
        // Initialize the array to hold counts
        $user = auth()->user();
        $organisation = Organisation::find($user->organisation_id);

        if ($organisation && $organisation->relationship_type == 'opd') {
            $opdName = $organisation->name;
            $opd_disability_count = [];

            $people = Person::where('opd_id', $organisation->id)
                ->whereNotNull('sex')
                ->where('sex', '<>', '')
                ->get();


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

                if (!isset($opd_disability_count[$ageGroup][$person->sex])) {
                    $opd_disability_count[$ageGroup][$person->sex] = 0;
                }

                // Increment the count for the current combination of age group and gender
                $opd_disability_count[$ageGroup][$person->sex]++;
            }
        }


        return view('opd-dashboard.opd-age-group', compact('opd_disability_count', 'opdName'));
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


    //PWDs Disability Category Count per district
    public static function getOpdtDisabilityCount()
    {
        $user = auth()->user();
        $organisation = Organisation::find($user->organisation_id);
        if (!($organisation && $organisation->relationship_type == 'opd')) {
            die('No such Organisation is found');
        }
        $opdName = $organisation->name;

        // Eager load disabilities within the specified district only.
        $people = Person::with('disabilities')->where('opd_id', $organisation->id)->paginate(100);

        $opdDisabilityCount = [];

        foreach ($people as $person) {
            foreach ($person->disabilities as $disability) {
                // Initialize disability count in the district array if not already present.
                $disabilityName = $disability->name ?? 'Unknown';
                if (!isset($opdDisabilityCount[$disabilityName])) {
                    $opdDisabilityCount[$disabilityName] = 0;
                }
                $opdDisabilityCount[$disabilityName]++;
            }
        }

        // Optionally sort the counts if needed
        arsort($opdDisabilityCount);

        return view('opd-dashboard.disability-counts', compact('opdDisabilityCount', 'opdName'));
    }

    public static function getOpdEducationByGender()
    {
        $user = auth()->user();
        $organisation = Organisation::find($user->organisation_id);
        if (!($organisation && $organisation->relationship_type == "opd")) {
            die('No such Organisation found');
        }

        $opdName = $organisation->name ?? 'Unknown';
        // Define education levels
        $educ_levels = [
            'formal Education' => 'Formal Education',
            'informal Education' => 'Informal Education',
            'no Education' => 'No Education'
        ];

        // Fetch education data
        $opdEducationData = Person::select('education_level', 'sex', DB::raw('count(*) as count'))
            ->where('opd_id', $organisation->id)
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
        $opd_education_levels = array_values($opdEducationData->pluck('education_level')->unique()->toArray());
        $genders = $opdEducationData->pluck('sex')->unique()->toArray();


        return view('opd-dashboard.opd-education-levels', compact('opdName', 'opd_education_levels', 'genders', 'opdEducationData'));
    }

    public static function getOpdEmploymentStatus()
    {
        $user = auth()->user();
        $organisation = Organisation::find($user->organisation_id);
        if (!($organisation && $organisation->relationship_type == 'opd')) {
            die('No such Organisation is found');
        }

        // OPD district name
        $opdName = $organisation->name ?? 'unknown';

        // Define the allowed employment statuses
        $employmentStatuses = [
            'formal employment' => 'Formal Employment',
            'self employment' => 'Self Employment',
            'unemployed' => 'Unemployed',
        ];

        // Fetch distinct employment statuses and gender from the Person model
        $opdEmploymentData = Person::select('employment_status', 'sex', DB::raw('count(*) as count'))
            ->where('opd_id', $organisation->id)
            ->whereNotNull('sex')
            ->where('sex', '<>', '')
            ->groupBy('employment_status', 'sex')
            ->get()
            ->map(function ($item) use ($employmentStatuses) {
                // Map employment status to its name
                $item->employment_status = $employmentStatuses[$item->employment_status] ?? 'Unknown';
                return $item;
            });
        $opdEmploymentLevels = $opdEmploymentData->pluck('employment_status')->unique()->toArray();
        $genders = $opdEmploymentData->pluck('sex')->unique()->toArray();

        return view('opd-dashboard.opd-employment-status', compact('opdEmploymentData', 'genders', 'employmentStatuses', 'opdName'));
    }

    public static function getOpdServiceProviders()
    {
        $user = auth()->user();
        $organisation = Organisation::find($user->organisation_id);
        if (!($organisation && $organisation->relationship_type == 'opd')) {
            die('No such Organisation is found');
        }

        // Fetch district name
        $user_id = $user->id;
        $opdName = $organisation->name ?? 'Unknown';
        $service_user_id = ServiceProvider::where('user_id', $user_id)->pluck('name', 'id');

        // Retrieve service providers operating in the district
        $service_providers = ServiceProvider::with('disability_categories', 'districts_of_operations')
            ->whereHas('districts_of_operations', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->get();

        // Initialize array to store counts
        $opdServiceCounts = [];

        // Calculate counts of service providers per disability category
        foreach ($service_providers as $service_provider) {
            foreach ($service_provider->disability_categories as $disability) {
                if (is_object($disability)) {
                    if (!isset($opdServiceCounts[$disability->name])) {
                        $opdServiceCounts[$disability->name] = 0;
                    }
                    $opdServiceCounts[$disability->name]++;
                }
            }
        }


        $opdTotalServices = $service_providers->count();

        // Sort counts array
        arsort($opdServiceCounts);

        // $queryParams = http_build_query([
        //     'user_id' => $user_id,
        //     'districts_of_operations' => ['name' => $opdName],
        //     'target_group' => '',  // 
        //     'disability_categories' => ['name' => '']
        // ]);

        $link = admin_url("service-providers");

        // Pass counts data to view
        return view('opd-dashboard.opd-service-counts', compact('opdServiceCounts', 'opdName', 'opdTotalServices', 'link'));
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
