<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\CounsellingCentre;
use App\Models\Event;
use App\Models\Group;
use App\Models\Institution;
use App\Models\Job;
use App\Models\NewsPost;
use App\Models\Person;
use App\Models\Product;
use App\Models\ServiceProvider;
use App\Models\Utils;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class ApiResurceController extends Controller
{

    use ApiResponser;

    public function people(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }

        return $this->success(
            Person::where([])
                ->limit(100)
                ->orderBy('id', 'desc')
                ->get(),
            $message = "Succesfully",
            200
        );
    }
    public function jobs(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }

        return $this->success(
            Job::where([])
                ->orderBy('id', 'desc')
                ->limit(100)
                ->get(),
            $message = "Succesfully",
        );
    }

    public function person_create(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }
        if (
            $r->name == null ||
            $r->sex == null
            // $r->subcounty_id == null
        ) {
            return $this->error('Some Information is still missing. Fill the missing information and try again.');
        }

        $image = "";
        if (!empty($_FILES)) {
            try {
                $image = Utils::upload_images_2($_FILES, true);
            } catch (Throwable $t) {
                $image = "no_image.jpg";
            }
        }

        $obj = new Person();
        $obj->id = $r->id;
        $obj->created_at = $r->created_at;
        $obj->name = $r->name;
        $obj->age = $r->age;
        $obj->address = $r->address;
        $obj->phone_number = $r->phone_number;
        $obj->email = $r->email;
        $obj->dob = $r->dob;
        $obj->sex = $r->sex;
        $obj->photo = $image;
        $obj->district_of_origin = $r->district_of_origin;
        $obj->district_of_residence = $r->district_of_residence;
        $obj->other_names = $r->other_names;
        $obj->id_number = $r->id_number;
        $obj->marital_status = $r->marital_status;
        $obj->religion = $r->religion;
        $obj->place_of_birth = $r->place_of_birth;
        $obj->position = $r->position;
        $obj->year_of_employment = $r->year_of_employment;
        $obj->district_id = $r->district_id;
        $obj->aspirations = $obj->aspirations;
        $obj->skills = $obj->skills;
        $obj->is_formal_education = $obj->is_formal_education;
        $obj->indicate_class = $obj->indicate_class;
        $obj->occupation = $obj->occupation;
        $obj->is_employed = $obj->is_employed;
        $obj->select_opd_or_du = $obj->select_opd_or_du;
        $obj->disability = $obj->disability;
        $obj->education_level = $obj->education_level;
        $obj->sub_county = $obj->sub_county;
        $obj->village = $obj->village;
        $obj->employment_status = $obj->employment_status;
        $obj->save();


        // $obj->association_id = $r->association_id;
        // $obj->administrator_id = $u->id;
        // $obj->group_id = $r->group_id;
        // $obj->name = $r->name;
        // $obj->address = $r->address;
        // $obj->parish = $r->parish;
        // $obj->village = $r->village;
        // $obj->phone_number = $r->phone_number;
        // $obj->email = $r->email;
        // $obj->district_id = $r->district_id;
        // $obj->subcounty_id = $r->subcounty_id;
        // $obj->disability_id = $r->disability_id;
        // $obj->phone_number_2 = $r->phone_number_2;
        // $obj->dob = $r->dob;
        // $obj->sex = $r->sex;
        // $obj->education_level = $r->education_level;
        // $obj->employment_status = $r->employment_status;
        // $obj->has_caregiver = $r->has_caregiver;
        // $obj->caregiver_name = $r->caregiver_name;
        // $obj->caregiver_sex = $r->caregiver_sex;
        // $obj->caregiver_phone_number = $r->caregiver_phone_number;
        // $obj->caregiver_age = $r->caregiver_age;
        // $obj->caregiver_relationship = $r->caregiver_relationship;
        // $obj->photo = $image;


        return $this->success(null, $message = "Succesfully registered!", 200);
    }

    public function person_update(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }

        $person = Person::find($r->id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        $person->update($r->all());
        return $this->success(null, $message = "Succesfully updated!", 200);
    }

    //function for deleting a person
    public function person_delete(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }

        $person = Person::find($r->id);
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
        $person->delete();
        return $this->success(null, $message = "Succesfully deleted!", 200);
    }

    public function groups()
    {
        return $this->success(Group::get_groups(), 'Success');
    }


    public function associations()
    {
        return $this->success(Association::where([])->orderby('id', 'desc')->get(), 'Success');
    }

    public function institutions()
    {
        return $this->success(Institution::where([])->orderby('id', 'desc')->get(), 'Success');
    }
    public function service_providers()
    {
        return $this->success(ServiceProvider::where([])->orderby('id', 'desc')->get(), 'Success');
    }
    public function counselling_centres()
    {
        return $this->success(CounsellingCentre::where([])->orderby('id', 'desc')->get(), 'Success');
    }
    public function products()
    {
        return $this->success(Product::where([])->orderby('id', 'desc')->get(), 'Success');
    }
    public function events()
    {
        return $this->success(Event::where([])->orderby('id', 'desc')->get(), 'Success');
    }
    public function news_posts()
    {
        return $this->success(NewsPost::where([])->orderby('id', 'desc')->get(), 'Success');
    }


    public function index(Request $r, $model)
    {

        $className = "App\Models\\" . $model;
        $obj = new $className;

        if (isset($_POST['_method'])) {
            unset($_POST['_method']);
        }
        if (isset($_GET['_method'])) {
            unset($_GET['_method']);
        }

        $conditions = [];
        foreach ($_GET as $k => $v) {
            if (substr($k, 0, 2) == 'q_') {
                $conditions[substr($k, 2, strlen($k))] = trim($v);
            }
        }
        $is_private = true;
        if (isset($_GET['is_not_private'])) {
            $is_not_private = ((int)($_GET['is_not_private']));
            if ($is_not_private == 1) {
                $is_private = false;
            }
        }
        if ($is_private) {

            $u = auth('api')->user();
            if ($u == null) {
                return $this->error('User account not found.');
            }
            $administrator_id = $u->id;

            if ($u == null) {
                return $this->error('User not found.');
            }
            $conditions['administrator_id'] = $administrator_id;
        }

        $items = [];
        $msg = "";

        try {
            $items = $className::where($conditions)->get();
            $msg = "Success";
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $msg = $e->getMessage();
        }

        if ($success) {
            return $this->success($items, 'Success');
        } else {
            return $this->error($msg);
        }
    }





    public function delete(Request $r, $model)
    {
        $administrator_id = Utils::get_user_id($r);
        $u = Administrator::find($administrator_id);


        if ($u == null) {
            return Utils::response([
                'status' => 0,
                'message' => "User not found.",
            ]);
        }


        $className = "App\Models\\" . $model;
        $id = ((int)($r->online_id));
        $obj = $className::find($id);


        if ($obj == null) {
            return Utils::response([
                'status' => 0,
                'message' => "Item already deleted.",
            ]);
        }


        try {
            $obj->delete();
            $msg = "Deleted successfully.";
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $msg = $e->getMessage();
        }


        if ($success) {
            return Utils::response([
                'status' => 1,
                'data' => $obj,
                'message' => $msg
            ]);
        } else {
            return Utils::response([
                'status' => 0,
                'data' => null,
                'message' => $msg
            ]);
        }
    }


    public function update(Request $r, $model)
    {
        $administrator_id = Utils::get_user_id($r);
        $u = Administrator::find($administrator_id);


        if ($u == null) {
            return Utils::response([
                'status' => 0,
                'message' => "User not found.",
            ]);
        }


        $className = "App\Models\\" . $model;
        $id = ((int)($r->online_id));
        $obj = $className::find($id);


        if ($obj == null) {
            return Utils::response([
                'status' => 0,
                'message' => "Item not found.",
            ]);
        }


        unset($_POST['_method']);
        if (isset($_POST['online_id'])) {
            unset($_POST['online_id']);
        }

        foreach ($_POST as $key => $value) {
            $obj->$key = $value;
        }


        $success = false;
        $msg = "";
        try {
            $obj->save();
            $msg = "Updated successfully.";
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $msg = $e->getMessage();
        }


        if ($success) {
            return Utils::response([
                'status' => 1,
                'data' => $obj,
                'message' => $msg
            ]);
        } else {
            return Utils::response([
                'status' => 0,
                'data' => null,
                'message' => $msg
            ]);
        }
    }
}
