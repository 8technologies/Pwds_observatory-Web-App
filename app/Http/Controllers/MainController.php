<?php

namespace App\Http\Controllers;

use App\Models\CounsellingCentre;
use App\Models\Course;
use App\Models\Disability;
use App\Models\District;
use App\Models\Event;
use App\Models\Job;
use App\Models\NewsPost;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class MainController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


  public function index()
  {

    /*     die("<h1>Something really cool is coming soon! 🥰</h1>"); */
    // $members = Administrator::where([])->orderBy('updated_at', 'desc')->limit(8)->get();
    // $profiles = [];
    // $_profiles = [];
    // foreach (Administrator::where([])->orderBy('updated_at', 'desc')->limit(15)->get() as $key => $v) {
    //   $profiles[] = $v;
    // }

    // foreach ($profiles as $key => $pro) {
    //   if ($pro->intro == null || strlen($pro->intro) < 3) {
    //     $pro->intro = "Hi there, I'm $pro->name . I call upon you to join the team!";
    //   }
    //   $_profiles[] = $pro;
    // }

    // $posts = [];
    // foreach (NewsPost::all() as $key => $v) {
    //   $posts[] = $v;
    // }
    // shuffle($posts);
    // $_posts = [];
    // $i = 0;
    // foreach ($posts as $key => $v) {
    //   $_posts[] = $v;
    //   $i++;
    //   if ($i > 2) {
    //     break;
    //   }
    // }

    // return view('index', [
    //   'members' => $members,
    //   'profiles' => $_profiles,
    //   'posts' => $_posts,
    // ]);

    //HIX CODE STARTS HERE

    //get percentage of persons by gender
    $female_pwd = Person::where('sex', 'Female')->count();
    $male_pwd = Person::where('sex', 'Male')->count();
    $total = $female_pwd + $male_pwd;

    $female_percentage = round($female_pwd / $total * 100);
    $male_percentage = round($male_pwd / $total * 100);

    //get top 5 districts with persons with disabilities
    $districts_count = DB::table('people')
      ->join('districts', 'people.district_id', '=', 'districts.id')
      ->select('districts.name as district', DB::raw('COUNT(*) as total'))
      ->groupBy('district')
      ->orderByRaw('total DESC')
      ->limit(5)
      ->get();


    //get persons by disability type
    $persons_by_disability = DB::table('people')
      ->join('disability_person', 'people.id', '=', 'disability_person.person_id')
      ->join('disabilities', 'disability_person.disability_id', '=', 'disabilities.id')
      ->select('disabilities.name', DB::raw('COUNT(*) as count'))
      ->groupBy('disabilities.name')
      ->get();


    //END OF HIX CODE

    return view('index', compact('female_percentage', 'male_percentage', 'total', 'districts_count', 'persons_by_disability'));
  }
  public function about_us()
  {
    return view('about-us');
  }
  public function our_team()
  {
    return view('our-team');
  }
  public function news_category()
  {
    return view('news-category');
  }



  public function dinner()
  {
    $p = Event::find(1);
    if ($p == null) {
      die("Post not found.");
    }
    return view('dinner', [
      'd' => $p
    ]);
  }

  public function news(Request $r)
  {
    $p = NewsPost::find($r->id);
    if ($p == null) {
      die("Post not found.");
    }

    $posts = [];
    foreach (NewsPost::all() as $key => $v) {
      $posts[] = $v;
    }
    shuffle($posts);
    $_posts = [];
    $i = 0;
    foreach ($posts as $key => $v) {
      $_posts[] = $v;
      $i++;
      if ($i > 2) {
        break;
      }
    }

    return view('news-post', [
      'p' => $p,
      'post' => $p,
      'posts' => $_posts,
    ]);
  }

  public function counseling_centres()
  {
    $counselingCentres = CounsellingCentre::all();

    foreach ($counselingCentres as $centre) {
      $district = District::find($centre->district_id);
      $centre->district_name = $district ? $district->name : 'Unknown District';
    }

    foreach ($counselingCentres as $centre) {
      $disability = Disability::find($centre->disability_id);
      $centre->disability_name = $disability ? $disability->name : 'Unknown Disability';
    }

    // Pass the data to the Blade view
    return view('counseling', ['counselingCentres' => $counselingCentres]);
  }

  /**
   * Fetch lastest resources paginated
   */
  public function resources(Request $r)
  {
    $resources = \App\Models\Resource::orderBy('id', 'desc')->paginate(12);
    return view('resources', [
      'resources' => $resources
    ]);
  }

  /**
   * Fetch a resource
   */
  public function resource(Request $r)
  {
    $resource = \App\Models\Resource::find($r->id);
    if ($resource == null) {
      die("Resource not found.");
    }
    return view('resource', [
      'resource' => $resource
    ]);
  }

  /**
   * Fetch service-providers sorted by name
   */
  public function service_providers(Request $r)
  {
    $service_providers = \App\Models\ServiceProvider::orderBy('name', 'asc')->paginate(12);
    return view('service-providers.index', [
      'service_providers' => $service_providers
    ]);
  }

  /**
   * Fetch a service-provider
   */
  public function service_provider(Request $r)
  {
    $service_provider = \App\Models\ServiceProvider::find($r->id);
    if ($service_provider == null) {
      die("Service provider not found.");
    }
    return view('service-providers.show', [
      'service_provider' => $service_provider
    ]);
  }

  /**
   * Fetch jobs paginated
   */
  public function jobs(Request $r)
  {
    $jobs = \App\Models\Job::orderBy('id', 'desc')->paginate(8);
    return view('jobs/index', [
      'jobs' => $jobs
    ]);
  }

  public function job_search(Request $request)
  {
    $titleSearch = $request->input('title_search');
    $locationSearch = $request->input('location_search');

    $jobs = Job::query()
      ->when($titleSearch, function ($query, $titleSearch) {
        return $query->where('title', 'like', "%{$titleSearch}%"); // Assuming you have a 'profession' field
      })
      ->when($locationSearch, function ($query, $locationSearch) {
        return $query->where('location', 'like', "%{$locationSearch}%");
      })
      ->where('status', 'Active')
      ->paginate(8); // Ensure the results are paginated

    return view('jobs.search_job', compact('jobs', 'titleSearch', 'locationSearch'));
  }

  /**
   * Fetch a job
   */
  public function job(Request $r)
  {
    $job = \App\Models\Job::find($r->id);
    if ($job == null) {
      die("Job not found.");
    }
    return view('job', [
      'job' => $job
    ]);
  }

  /**
   * Fetch disabilities
   */
  public function disabilities(Request $r)
  {
    $disabilities = \App\Models\Disability::paginate(12);
    return view('disabilities.index', [
      'disabilities' => $disabilities
    ]);
  }

  /**
   * Fetch a disability
   */
  public function disability(Request $r)
  {
    $disability = \App\Models\Disability::find($r->id);
    if ($disability == null) {
      die("Disability not found.");
    }
    return view('disabilities.show', [
      'disability' => $disability
    ]);
  }

  /**
   * Fetch innovations
   */
  public function innovations(Request $r)
  {
    $innovations = \App\Models\Innovation::orderBy('id', 'desc')->paginate(8);
    return view('innovations', [
      'innovations' => $innovations
    ]);
  }

  /**
   * Fetch an innovation
   */
  public function innovation(Request $r)
  {
    $innovation = \App\Models\Innovation::find($r->id);
    if ($innovation == null) {
      die("Innovation not found.");
    }
    return view('innovation', [
      'innovation' => $innovation
    ]);
  }


  public function members()
  {
    $members = Administrator::where([])->orderBy('id', 'desc')->limit(12)->get();
    return view('members', [
      'members' => $members
    ]);
  }

  function generate_class()
  {

    $data = 'id, created_at, updated_at, title, theme, photo, details, prev_event_title, number_of_attendants, number_of_speakers, number_of_experts, venue_name, venue_photo, venue_map_photo, event_date, address, gps_latitude, gps_longitude, video';

    $modelName = 'EventModel';
    $endPoint = 'events';
    $tableName = 'events';
    //$array = preg_split('/\r\n|\n\r|\r|\n/', $data);
    $array = explode(',', $data);
    $generate_vars = MainController::generate_vars($array);
    $fromJson = MainController::fromJson($array);
    $from_json = MainController::from_json($array);
    $toJson = MainController::to_json($array);
    $create_table = MainController::create_table($array, $modelName);
    return <<<EOT
<pre>
import 'package:nudipu/utils/Utils.dart';
import 'package:sqflite/sqflite.dart';

import 'RespondModel.dart';

class $modelName {
  static String endPoint = "$endPoint";
  static String tableName = "$tableName";

  $generate_vars

  static fromJson(dynamic m) {
    $modelName obj = new $modelName();
    if (m == null) {
      return obj;
    }
    
  $fromJson
  return obj;
}

  


  static Future&lt;List&lt;$modelName&gt;&gt; getLocalData({String where: "1"}) async {
    List&lt;$modelName&gt; data = [];
    if (!(await $modelName.initTable())) {
      Utils.toast("Failed to init dynamic store.");
      return data;
    }

    Database db = await Utils.dbInit();
    if (!db.isOpen) {
      return data;
    }

    List&lt;Map&gt; maps = await db.query($modelName.tableName, where: where);

    if (maps.isEmpty) {
      return data;
    }
    List.generate(maps.length, (i) {
      data.add($modelName.fromJson(maps[i]));
    });

    return data;
  }


  static Future&lt;List&lt;$modelName&gt;&gt; getItems({String where = '1'}) async {
    List&lt;$modelName&gt; data = await getLocalData(where: where);
    if (data.isEmpty) {
      await $modelName.getOnlineItems();
      data = await getLocalData(where: where);
    } else {
      data = await getLocalData(where: where);
      $modelName.getOnlineItems();
    }
    data.sort((a, b) => b.id.compareTo(a.id));
    return data;
  }

  static Future&lt;List&lt;$modelName&gt;&gt; getOnlineItems() async {
    List&lt;$modelName&gt; data = [];

    RespondModel resp =
        RespondModel(await Utils.http_get($modelName.endPoint, {}));

    if (resp.code != 1) {
      return [];
    }

    Database db = await Utils.dbInit();
    if (!db.isOpen) {
      Utils.toast("Failed to init local store.");
      return [];
    }

    if (resp.data.runtimeType.toString().contains('List')) {
      if (await Utils.is_connected()) {
        await $modelName.deleteAll();
      }

      await db.transaction((txn) async {
        var batch = txn.batch();

        for (var x in resp.data) {
          $modelName sub = $modelName.fromJson(x);
          try {
            batch.insert(tableName, sub.toJson(),
                conflictAlgorithm: ConflictAlgorithm.replace);
          } catch (e) {}
        }

        try {
          await batch.commit(continueOnError: true);
        } catch (e) {}
      });
    }

    return [];

    return data;
  }

  save() async {
    Database db = await Utils.dbInit();
    if (!db.isOpen) {
      Utils.toast("Failed to init local store.");
      return;
    }

    await initTable();

    try {
      await db.insert(
        tableName,
        toJson(),
        conflictAlgorithm: ConflictAlgorithm.replace,
      );
    } catch (e) {
      Utils.toast("Failed to save student because \${e.toString()}");
    }
  }

  toJson() {
    return {
     $toJson
    };
  }

  static Future&lt;bool&gt; initTable() async {
    Database db = await Utils.dbInit();
    if (!db.isOpen) {
      return false;
    }

    String sql = "$create_table";

    try {
      //await db.delete(tableName);

      await db.execute(sql);
    } catch (e) {
      Utils.log('Failed to create table because \${e.toString()}');

      return false;
    }

    return true;
  }

  static deleteAll() async {
    if (!(await $modelName.initTable())) {
      return;
    }
    Database db = await Utils.dbInit();
    if (!db.isOpen) {
      return false;
    }
    await db.delete(tableName);
  }
}
</pre>
EOT;

    return view('generate-class', [
      'modelName' => $modelName,
      'endPoint' => $endPoint,
      'fromJson' => MainController::fromJson($vars),
    ]);
  }

  function generate_variables($data)
  {

    MainController::createNew($recs);
    MainController::from_json($recs);
    MainController::fromJson($recs);
    MainController::generate_vars($recs);
    MainController::create_table($recs, 'people');
    //MainController::to_json($recs);
  }


  function createNew($recs)
  {

    $_data = "";

    foreach ($recs as $v) {
      $key = trim($v);

      $_data .= "\$obj->{$key} =  \$r->{$key};<br>";
    }

    return $_data;
  }


  function fromJson($recs)
  {

    $_data = "";

    foreach ($recs as $v) {
      $key = trim($v);
      if (strlen($key) < 1) {
        continue;
      }
      if ($key == 'id') {
        $_data .= "obj.{$key} = Utils.int_parse(m['{$key}']);<br>";
      } else {
        $_data .= "obj.{$key} = Utils.to_str(m['{$key}'],'');<br>";
      }
    }
    return $_data;
  }



  function create_table($recs, $modelName)
  {

    $__t = '${' . $modelName . '.tableName}';
    $_data = "CREATE TABLE  IF NOT EXISTS  $__t (  " . '"';
    $i = 0;
    $len = count($recs);
    foreach ($recs as $v) {
      $key = trim($v);
      $i++;
      if (strlen($key) < 1) {
        continue;
      }

      $_data .= '<br>"';
      if ($key == 'id') {
        $_data .= 'id INTEGER PRIMARY KEY';
      } else {
        '"' . $_data .= " $key TEXT";
      }


      if ($i  != $len) {
        $_data .= ',"';
      }
    }

    $_data .= ')';
    return $_data;
  }


  function from_json($recs)
  {

    $_data = "";
    foreach ($recs as $v) {

      $key = trim($v);
      if (strlen($key) < 2) {
        continue;
      }
      $_data .= "$key : $key,<br>";
    }

    return $_data;
  }


  function to_json($recs)
  {
    $_data = "";
    foreach ($recs as $v) {
      $key = trim($v);
      if (strlen($key) < 2) {
        continue;
      }
      $_data .= "'$key' : $key,<br>";
    }

    return $_data;
  }

  function generate_vars($recs)
  {

    $_data = "";
    foreach ($recs as $v) {
      $key = trim($v);
      if (strlen($key) < 1) {
        continue;
      }

      if ($key == 'id') {
        $_data .= "int $key = 0;<br>";
      } else {
        $_data .= "String $key = \"\";<br>";
      }
    }

    return $_data;
  }
}
