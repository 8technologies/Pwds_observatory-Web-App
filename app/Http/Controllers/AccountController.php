<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AccountController extends BaseController
{
    /**
     * GET /register
     * Show the PWD signup form with districts & disabilities.
     */
    public function register()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $districts    = \App\Models\District::pluck('name','id');
            $docxIds = [
        3,   // Deaf
        4,   // Mental Disability
        5,   // Intellectual Disability
        6,   // Acquired Brain Injury
        7,   // Physical Disability
        8,   // Albinism
        9,   // Dwarfism
        10,  // Hard Of Hearing
        11,  // Epilepsy
        12,  // Cerebral Palsy
        13,  // Hydrocephalus
        29,  // Speech Impairment
        54,  // Low vision
        49,  // Partially blind
        52,  // Totally blind
    ];

    // 2) fetch just those, in exactly that order
    $disabilities = \App\Models\Disability::whereIn('id', $docxIds)
        ->orderByRaw('FIELD(id,' . implode(',', $docxIds) . ')')
        ->pluck('name', 'id');


        return view('register', compact('districts','disabilities'));
    }

    /**
     * POST /account-activation
     * Handle PWD registration.
     */
 public function activateAccount(Request $request)
    {
        // 1) Base validation (phone_number must be exactly 10 digits, starting 0)
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|confirmed|min:4',
            'phone_number' => [
                'required',
                'regex:/^0\d{9}$/',                  // 10 digits, starting 0
                'unique:people,phone_number',        // Must be unique in people table
            ],
            'district'       => 'required|exists:districts,id',
            'disability'     => 'required|exists:disabilities,id',
            'sex'         => ['required', Rule::in(['Male','Female'])],
            'village'        => 'required|string|max:255',
            'dob'            => 'required|date|before:today',
        ], [
            'phone_number.regex'  => 'Phone Number must be 10 digits starting with 0 (e.g. 0762045035).',
            'phone_number.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'district.exists' => 'Please select a valid district.',
            'disability.exists' => 'Please select a valid disability type.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 3) Grab the clean data
        $data = $validator->validated();

        try {
            // Compute age
            $age = Carbon::parse($data['dob'])->age;

            // 4) Create the User
            $user = new User;
            $user->name             = $data['name'];
            $user->username         = $data['email'];
            $user->email            = $data['email'];
            $user->password         = Hash::make($data['password']);
            $user->approved         = 0;
            $user->activation_token = Str::random(60);
            $user->save();
            $user->assignRole('pwd');

            // 5) Create the Person
            $person = Person::create([
                'user_id'       => $user->id,
                'name'          => $data['name'],
                'email'         => $data['email'],
                'phone_number'  => $data['phone_number'],
                'district_id'   => $data['district'],
                'disability'    => $data['disability'],
                'sex'           => $data['sex'],
                'village'       => $data['village'],
                'dob'           => $data['dob'],
                'age'           => $age,
                'profiler'      => 'Self Profiled',
                'is_verified'   => 0,
            ]);

            $person->disabilities()->attach($data['disability']);

            // 6) Send activation email
            $user->sendActivationEmail($user->activation_token);

            return redirect('login')
                   ->with('success', 'Registration successful! Please check your email to activate your account, then login with your credentials.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /** GET /activate?email=…&token=… */
    public function activate(Request $request)
    {
        $user = User::where('email', $request->email)
                    ->where('activation_token', $request->token)
                    ->first();

        if (! $user) {
            return view('activation-failed');
        }

        $user->activation_token = null;
        $user->approved         = 1;
        $user->save();

        return view('activation-success');
    }
    /**
     * GET /login
     */
    public function login()
    {
        //dd("Yoo");
        if (Auth::check()) {
           //dd('Yooo');
            $user = Auth::user();
            if ($user->isRole('district-union')) {
                return redirect('/du-dashboard');
            } elseif ($user->isRole('du-agent')) {
                return redirect('/du-dashboard');     
            } elseif ($user->isRole('opd')) {
                return redirect('/opd-dashboard');
            } elseif ($user->isRole('administrator') || $user->isRole('nudipu')) {
                return redirect('/dashboard');
            } else {
                return redirect('/pwd-dashboard');
            }
        }
        return view('login');
    }

    /**
     * POST /login
     */
    public function login_post(Request $r)
    {
        $credentials = $r->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt([
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ], true)) {
            $user = Auth::user();
            if ($user->isRole('district-union')) {
                return redirect('/du-dashboard');
            } elseif ($user->isRole('du-agent')) {
                return redirect('/du-dashboard');     
            } elseif ($user->isRole('opd')) {
                return redirect('/opd-dashboard');
            } elseif ($user->isRole('administrator') || $user->isRole('nudipu')) {
                return redirect('/dashboard');
            } else {
                return redirect('/pwd-dashboard');
            }
        }

        return back()
            ->withErrors(['password' => 'Wrong email or password.'])
            ->withInput();
    }

    /** GET /account-details */
    public function account_details()
    {
        return view('account-details', ['user' => Auth::user()]);
    }

    /** POST /account-details */
    public function account_details_post(Request $r)
    {
        $data = $r->validate([
            'name'     => 'required|string|min:2',
            'username' => 'required|email',
        ]);

        $user = Auth::user();
        $user->name     = $data['name'];
        $user->username = $data['username'];
        $user->save();

        return back()->with('success','Profile updated.');
    }

    /** GET /dashboard */
    public function dashboard()
    {
        return view('account-dashboard');
    }

    /** GET /logout */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}