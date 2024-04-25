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

class District_Union_Dashboard
{


    //Function for returning count of people with disability in a district grouped by sex
    public static function getGenderCountDisability()
    {
        // Get the district union ID of the logged-in user
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        $districtName = District::where('id', $district_union_id)->first()->name;

        // Get distinct sexes
        $sex = Person::whereNotNull('sex')->distinct('sex')->pluck('sex')->toArray();

        // Get gender count data for the logged district union
        $gender_count = Person::select('districts.name as district', DB::raw('count(*) as count'), 'people.sex as sex')
            ->join('districts', 'people.district_id', '=', 'districts.id')
            ->where('districts.id', $district_union_id) // Filter by district union ID
            ->groupBy('districts.name', 'people.sex')
            ->whereNotNull('people.sex') // Eliminate data where 'sex' is null
            ->where('people.sex', '<>', 'N/A') // Eliminate data where 'sex' is 'N/A'
            ->get();

        return view('du-dashboard.district_gender_count', compact('sex', 'gender_count', 'districtName'));
    }

    //PWDs Disability Category Count per district
    public static function getDistrictByGenderAndAge()
    {
        // Initialize the array to hold counts
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        $districtName = District::where('id', $district_union_id)->first()->name;
        $district_disability_counts = [];

        // Get all people, eager loading their disabilities
        $people = Person::where('district_id', $district_union_id)
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

            if (!isset($district_disability_counts[$ageGroup][$person->sex])) {
                $district_disability_counts[$ageGroup][$person->sex] = 0;
            }

            // Increment the count for the current combination of age group and gender
            $district_disability_counts[$ageGroup][$person->sex]++;
        }

        return view('du-dashboard.district_age_group', compact('district_disability_counts', 'districtName'));
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
    public static function getDistrictDisabilityCount()
    {
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        // Assuming District::find() is sufficient since we already know the district ID.
        $districtName = District::find($district_union_id)->name ?? 'Unknown';

        // Eager load disabilities within the specified district only.
        $people = Person::with('disabilities')->where('district_id', $district_union_id)->get();

        $districtDisabilityCounts = [];

        foreach ($people as $person) {
            foreach ($person->disabilities as $disability) {
                // Initialize disability count in the district array if not already present.
                $disabilityName = $disability->name ?? 'Unknown';
                if (!isset($districtDisabilityCounts[$disabilityName])) {
                    $districtDisabilityCounts[$disabilityName] = 0;
                }
                $districtDisabilityCounts[$disabilityName]++;
            }
        }

        // Optionally sort the counts if needed
        arsort($districtDisabilityCounts);

        return view('du-dashboard.district_disability_category', compact('districtDisabilityCounts', 'districtName'));
    }

    public static function getDistrictEducationByGender()
    {
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        // Fetch district name
        $districtName = District::find($district_union_id)->name ?? 'Unknown';

        // Define education levels
        $educ_levels = [
            'formal education' => 'Formal Education',
            'informal education' => 'Informal Education',
            'no education' => 'No Education'
        ];

        // Fetch education data
        $districtEducationData = Person::select('education_level', 'sex', DB::raw('count(*) as count'))
            ->where('district_id', $district_union_id)
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
        $district_educationLevels = array_values($districtEducationData->pluck('education_level')->unique()->toArray());
        $genders = $districtEducationData->pluck('sex')->unique()->toArray();


        // dd($districtEducationData, $district_educationLevels, $genders);


        return view('du-dashboard.district_education_levels', compact('districtName', 'district_educationLevels', 'genders', 'districtEducationData'));
    }

    public static function getDistrictEmploymentStatus()
    {
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        // Fetch district name
        $districtName = District::find($district_union_id)->name ?? 'Unknown';

        // Define the allowed employment statuses
        $employmentStatuses = [
            'formal employment' => 'Formal Employment',
            'self employment' => 'Self Employment',
            'unemployed' => 'Unemployed',
        ];

        // Fetch distinct employment statuses and gender from the Person model
        $employmentData = Person::select('employment_status', 'sex', DB::raw('count(*) as count'))
            ->where('district_id', $district_union_id)
            ->whereNotNull('sex')
            ->where('sex', '<>', '')
            ->groupBy('employment_status', 'sex')
            ->get()
            ->map(function ($item) use ($employmentStatuses) {
                // Map employment status to its name
                $item->employment_status = $employmentStatuses[$item->employment_status] ?? 'Unknown';
                return $item;
            });

        $districtEmploymentLevels = $employmentData->pluck('employment_status')->unique()->toArray();
        $genders = $employmentData->pluck('sex')->unique()->toArray();

        return view('du-dashboard.district_employment_status', compact('employmentData', 'genders', 'employmentStatuses', 'districtName'));
    }

    public static function getDistrictServiceProviders()
    {
        $user = auth()->user();
        $organisation = Organisation::where('user_id', $user->id)->first();
        $district_union_id = $organisation->district_id;

        // Fetch district name
        $districtName = District::find($district_union_id)->name ?? 'Unknown';

        // Retrieve service providers operating in the district
        $service_providers = ServiceProvider::with('disability_categories', 'districts_of_operations')
            ->whereHas('districts_of_operations', function ($query) use ($district_union_id) {
                $query->where('districts.id', $district_union_id);
            })
            ->get();

        // Initialize array to store counts
        $serviceCounts = [];

        // Calculate counts of service providers per disability category
        foreach ($service_providers as $service_provider) {
            foreach ($service_provider->disability_categories as $disability) {
                if (is_object($disability)) {
                    if (!isset($serviceCounts[$disability->name])) {
                        $serviceCounts[$disability->name] = 0;
                    }
                    $serviceCounts[$disability->name]++;
                }
            }
        }

        // Sort counts array
        arsort($serviceCounts);

        // Pass counts data to view
        return view('du-dashboard.district_service_provider', compact('serviceCounts', 'districtName'));
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
