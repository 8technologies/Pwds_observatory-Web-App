<?php

namespace App\Models;

use App\Admin\Extensions\Column\OpenMap;
use App\Mail\CreatedDistrictUnionMail;
use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'name',
        'registration_number',
        'date_of_registration',
        'mission',
        'vision',
        'core_values',
        'brief_profile',
        'membership_type',
        'district_id',
        'physical_address',
        'website',
        'attachments',
        'logo',
        'certificate_of_registration',
        'constitution',
        'admin_email',
        'valid_from',
        'valid_to',
        'relationship_type',
    ];

    public function setAttachmentsAttribute($value)
    {
        $this->attributes['attachments'] = json_encode($value);
    }

    public function getAttachmentsAttribute($value)
    {
        return json_decode($value);
    }

    public function districtsOfOperation()
    {
        return $this->belongsToMany(District::class)->withTimestamps();
    }

    public function districtOfOperation()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Programs or initiatives run by this organisation
     */
    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function leaderships()
    {
        return $this->hasMany(Leadership::class);
    }

    public function parentOrganisation()
    {
        return $this->hasOne(Organisation::class, 'parent_organisation_id')->where('id', $this->id);
    }

    public function opds()
    {
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type', 'opd');
    }

    public function district_unions()
    {
        return $this->hasMany(Organisation::class, 'parent_organisation_id')->where('relationship_type', 'du');
    }

    public function contact_persons()
    {
        return $this->hasMany(OrganisationContactPerson::class);
    }

    public function memberships()
    {
        return $this->hasMany(Organisation::class);
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public static function get_region($district_id)
    {
        $district = District::with('region')->find($district_id);
        if (!$district) {
            return null;
        }
        return $district->region->name;
    }


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }


    public static function updateRegionIdForOldRecords()
    {
        // Find organisations with region_id set to zero
        $organisationsToUpdate = self::where('region_id', 0)->get();

        foreach ($organisationsToUpdate as $organisation) {
            $districtId = $organisation->district_id;

            // Retrieve the corresponding district and its region
            $district = District::with('region')->find($districtId);

            // If district and region are found, update the organisation's region_id
            if ($district && $district->region) {
                $organisation->update(['region_id' => $district->region->id]);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $district = District::find($model->district_id);
            if (!$district) {
                return 'District not found';
            }
            $model->region_id = $district->region_id;

            if ($this->relationship_type == 'opd') {
                $model->opd_id = $this->id;
            }
            if ($this->relationship_type == 'du') {
                $model->district_id = $model->district_id;
            }


            if ($model->relationship_type == 'du') {
                $admin_password = session('password') ?? '';

                try {
                    // Ensuring model has a valid email
                    if (isset($model->admin_email) && filter_var($model->admin_email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($model->admin_email)->send(new CreatedDistrictUnionMail($model->name, $model->admin_email, $admin_password));
                    } else {
                        Log::error('Invalid admin email: ' . $model->admin_email);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                    return "Mail cannot be sent";
                }
            }
        });

        static::saving(function ($model) {
            if ($model->isCreating() && $model->relationship_type == 'du') {
                $du_exists = self::where('district_id', $model->district_id)
                    ->where('relationship_type', 'du')
                    ->exists();

                if ($du_exists) {
                    // Handling error in a model context might differ, depending on your application structure
                    Log::error('District Union already exists for the specified district');
                    throw new \Exception('District Union already exists for the specified district');
                }

                // Generate random password for user and send it to the user's email
                $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
                $new_password = substr(str_shuffle($alpha_list), 0, 8);
                $hashed_password = Hash::make($new_password);

                $admin_email = $model->admin_email;

                // Check if user exists
                $admin = User::where('email', $admin_email)->first();

                if ($admin == null) {
                    $admin = User::create([
                        'username' => $admin_email,
                        'email' => $admin_email,
                        'password' => $hashed_password,
                        'name' => $model->name,
                        'avatar' => $model->logo,
                    ]);

                    $admin->assignRole('district-union');
                }

                // Assign relevant fields to the model
                $model->user_id = $admin->id;
                $model->relationship_type = 'du';
                $model->parent_organisation_id = session('organisation_id');

                // Store the new password in the session for email use
                session(['password' => $new_password]);

                // Send the email
                try {
                    Mail::to($admin_email)->send(new CreatedDistrictUnionMail($model->name, $admin_email, $new_password));
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        });

        static::updating(function ($model) {
            // Retrieve the input values
            if ($model->relationship_type == 'du') {
                $password = request()->input('password');
                $new_password = request()->input('new_password');
                $confirm_new_password = request()->input('confirm_new_password');

                if ($new_password !== $confirm_new_password) {
                    // Handling error 
                    Log::error('Passwords do not match. Please check the new password and try again.');
                    throw new \Exception('Passwords do not match. Please check the new password and try again.');
                }

                // Checking if both password fields are not empty
                if (!empty($password) && !empty($new_password)) {
                    // Assuming $model is the Administrator instance being updated
                    if (Hash::check($password, $model->password, ['rounds' => 12])) {
                        // Old password is correct, update to new password
                        $model->password = Hash::make($new_password);
                        Log::info('Password updated successfully.');
                    } else {
                        // Old password is incorrect
                        Log::error('Old password is incorrect. Please check the old password and try again.');
                        throw new \Exception('Old password is incorrect. Please check the old password and try again.');
                    }
                }
            }
        });
    }
}
