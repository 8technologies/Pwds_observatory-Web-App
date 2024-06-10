<?php

namespace App\Models;

use App\Admin\Extensions\Column\OpenMap;
use App\Mail\CreatedDistrictUnionMail;
use Error;
use Exception;
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
        if ($district->region == null) {
            return 'N/A';
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
            $model = self::do_validate($model);
            $model = self::do_validate_nopd($model);
            return true;
        });

        static::updating(function ($model) {
            $model = self::do_validate($model);
            $model = self::do_validate_nopd($model);
            return true;
        });

        static::created(function ($model) {
            self::do_finalization($model);
            self::do_finalization_nopd($model);
        });

        static::updated(function ($model) {
            self::do_finalization($model);
            self::do_finalization_nopd($model);
        });
    }


    public static function do_validate($model)
    {
        $district = District::find($model->district_id);
        if (!$district) {
            throw new Error('District not found.');
        }


        $model->region_id = $district->region_id;

        if ($model->relationship_type == 'du') {
            if (!filter_var($model->admin_email, FILTER_VALIDATE_EMAIL)) {
                throw new Error('Invalid email address. => ' . $model->admin_email . " <= ");
            }

            $model->district_id = $model->district_id;
            $opd_with_same_id = self::where('district_id', $model->district_id)
                ->where('relationship_type', 'du')
                ->first();
            if ($opd_with_same_id != null) {
                if ($opd_with_same_id->id != $model->id) {
                    throw new Error('District Union already exists for the specified district. id #' . $opd_with_same_id->id);
                }
            }
        }

        return $model;
    }

    public static function do_finalization($model)
    {

        if ($model->relationship_type == 'du') {
            $exist = User::where('email', $model->admin_email)->first();
            $created_new_admin = false;
            $update_admin = false;
            if ($exist != null) {
                $org = Organisation::find($exist->organisation_id);
                if ($org == null) {
                    $update_admin = true;
                }
            } else {
                $created_new_admin = true;
            }

            if ($update_admin) {
                $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
                $new_password = substr(str_shuffle($alpha_list), 0, 8);
                $hashed_password = Hash::make($new_password);
                $exist->password = $hashed_password;
                $exist->username = $model->admin_email;
                $exist->email = $model->admin_email;
                $exist->password = $new_password;
                $exist->approved = 1;
                $DIS = District::find($model->district_id);
                $exist->first_name = $DIS->name . ' DU';
                $exist->last_name = 'Admin';
                $exist->organisation_id = $model->id;
                $exist->save();
                $exist->assignRole('district-union');
                try {
                    $model->reset_admin_pass();
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }


            if ($created_new_admin) {
                $exist = new User();
                $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
                $new_password = substr(str_shuffle($alpha_list), 0, 8);
                $hashed_password = Hash::make($new_password);
                $exist->password = $hashed_password;
                $exist->username = $model->admin_email;
                $exist->email = $model->admin_email;
                $exist->password = $new_password;
                $exist->approved = 1;
                $DIS = District::find($model->district_id);
                $exist->first_name = $DIS->name . ' DU';
                $exist->last_name = 'Admin';
                $exist->organisation_id = $model->id;
                $exist->save();
                $exist->assignRole('district-union');
                try {
                    $model->reset_admin_pass();
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        }
    }

    //reset admin password
    public function reset_admin_pass()
    {
        $model = $this;
        $exist = User::where('email', $model->admin_email)->first();
        $created_new_admin = false;
        $update_admin = false;
        $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
        $new_password = substr(str_shuffle($alpha_list), 0, 8);
        $hashed_password = Hash::make($new_password);

        if ($exist != null) {
            $org = Organisation::find($exist->organisation_id);
            if ($org == null) {
                $update_admin = true;
            }
        } else {
            $created_new_admin = true;
        }

        if ($update_admin) {
            $exist->password = $hashed_password;
            $exist->username = $model->admin_email;
            $exist->email = $model->admin_email;
            $exist->approved = 1;
            $DIS = District::find($model->district_id);
            $exist->first_name = $DIS->name . ' DU';
            $exist->last_name = 'Admin';
            $exist->organisation_id = $model->id;
            $exist->name = $exist->first_name . ' ' . $exist->last_name;
            $exist->save();
        }


        if ($created_new_admin) {
            $exist = new User();
            $exist->username = $model->admin_email;
            $exist->email = $model->admin_email;
            $exist->password = $hashed_password;
            $exist->approved = 1;
            $DIS = District::find($model->district_id);
            $exist->first_name = $DIS->name . ' DU';
            $exist->last_name = 'Admin';
            $exist->name = $exist->first_name . ' ' . $exist->last_name;
            $exist->organisation_id = $model->id;
            $exist->save();
            $exist->assignRole('district-union');
        }

        $du_admin = User::where('email', $model->admin_email)->first();
        //msg to admin to login using $new_password
        $url = url('login');
        $url = $url . "?my_email=" . $model->admin_email;
        $url = $url . "&my_pass=" . $new_password;

        if ($du_admin != null) {
            $exist->password = $hashed_password;
            $exist->save();

            $body = <<<EOF
            Dear Sir/Madam,
            <br>
            <br>
            Your password has been reset. You can now login to the Persons with Disability Observatory using the following credentials:
            <br>
            <br><b>EMAIL:</b> {$model->admin_email}
            <br><b>PASSWORD:</b> {$new_password}
            <br>
            <br>
            <b>OR click the link below to login:</b>
            <br>
            <br><b>LINK:</b> {$url}
            <br>
            <br>
            Regards,
            <br>
            8Tech Team.
        EOF;

            $data = [
                'email' => $model->admin_email,
                'name' => $du_admin->name,
                'subject' => 'Password Reset - ' . env('APP_NAME') . date('Y-m-d H:i:s'),
                'body' => $body
            ];
            Utils::mail_send($data);
        }
    }

    // public static function do_validate_nopd($model)
    // {
    //     // $district = District::find($model->district_id);
    //     // if (!$district) {
    //     //     throw new Error('District not found.');
    //     // }


    //     // $model->region_id = $district->region_id;
    //     //Validating the opd
    //     if ($model->relationship_type == 'opd') {
    //         if (!filter_var($model->admin_email, FILTER_VALIDATE_EMAIL)) {
    //             throw new Error('Invalid email address. => ' . $model->admin_email . " <= ");
    //         }

    //         $model->opd_id = $model->opd_id;
    //         $opd_with_same_id = self::where('opd_id', $model->opd)
    //             ->where('relationship_type', 'opd')
    //             ->first();
    //         if ($opd_with_same_id != null) {
    //             if ($opd_with_same_id->id != $model->id) {
    //                 throw new Error('NOPD already exists for a different organisation. id #' . $opd_with_same_id->id);
    //             }
    //         }
    //     }

    //     return $model;
    // }

    // public static function do_finalization_nopd($model)
    // {

    //     if ($model->relationship_type == 'opd') {
    //         $exist = User::where('email', $model->admin_email)->first();
    //         $created_new_admin = false;
    //         $update_admin = false;
    //         if ($exist != null) {
    //             $org = Organisation::find($exist->organisation_id);
    //             if ($org == null) {
    //                 $update_admin = true;
    //             }
    //         } else {
    //             $created_new_admin = true;
    //         }

    //         if ($update_admin) {
    //             $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
    //             $new_password = substr(str_shuffle($alpha_list), 0, 8);
    //             $hashed_password = Hash::make($new_password);
    //             $exist->password = $hashed_password;
    //             $exist->username = $model->admin_email;
    //             $exist->email = $model->admin_email;
    //             $exist->password = $new_password;
    //             $exist->approved = 1;
    //             // $DIS = District::find($model->district_id);
    //             // $exist->first_name = $DIS->name . ' DU';
    //             $exist->last_name = 'Admin';
    //             $exist->organisation_id = $model->id;
    //             $exist->save();
    //             $exist->assignRole('opd');
    //             try {
    //                 $model->reset_admin_pass();
    //             } catch (\Exception $e) {
    //                 Log::error('Failed to send email: ' . $e->getMessage());
    //             }
    //         }


    //         if ($created_new_admin) {
    //             $exist = new User();
    //             $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
    //             $new_password = substr(str_shuffle($alpha_list), 0, 8);
    //             $hashed_password = Hash::make($new_password);
    //             $exist->password = $hashed_password;
    //             $exist->username = $model->admin_email;
    //             $exist->email = $model->admin_email;
    //             $exist->password = $new_password;
    //             $exist->approved = 1;
    //             // $DIS = District::find($model->district_id);
    //             // $exist->first_name = $DIS->name . ' DU';
    //             $exist->last_name = 'Admin';
    //             $exist->organisation_id = $model->id;
    //             $exist->save();
    //             $exist->assignRole('opd');
    //             try {
    //                 $model->reset_admin_pass();
    //             } catch (\Exception $e) {
    //                 Log::error('Failed to send email: ' . $e->getMessage());
    //             }
    //         }
    //     }
    // }

    // public function reset_nopd_admin_pass()
    // {
    //     $model = $this;
    //     $exist = User::where('email', $model->admin_email)->first();
    //     $created_new_admin = false;
    //     $update_admin = false;
    //     $alpha_list = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz1234567890';
    //     $new_password = substr(str_shuffle($alpha_list), 0, 8);
    //     $hashed_password = Hash::make($new_password);

    //     if ($exist != null) {
    //         $org = Organisation::find($exist->organisation_id);
    //         if ($org == null) {
    //             $update_admin = true;
    //         }
    //     } else {
    //         $created_new_admin = true;
    //     }

    //     if ($update_admin) {
    //         $exist->password = $hashed_password;
    //         $exist->username = $model->admin_email;
    //         $exist->email = $model->admin_email;
    //         $exist->approved = 1;
    //         // // $DIS = District::find($model->district_id);
    //         $exist->first_name = $model->name;
    //         $exist->last_name = 'Admin';
    //         $exist->organisation_id = $model->id;
    //         $exist->name = $exist->first_name . ' ' . $exist->last_name;
    //         $exist->save();
    //     }


    //     if ($created_new_admin) {
    //         $exist = new User();
    //         $exist->username = $model->admin_email;
    //         $exist->email = $model->admin_email;
    //         $exist->password = $hashed_password;
    //         $exist->approved = 1;
    //         // $DIS = District::find($model->district_id);
    //         $exist->first_name = $model->name;
    //         $exist->last_name = 'Admin';
    //         $exist->name = $exist->first_name . ' ' . $exist->last_name;
    //         $exist->organisation_id = $model->id;
    //         $exist->save();
    //         $exist->assignRole('opd');
    //     }

    //     $nopd_admin = User::where('email', $model->admin_email)->first();
    //     //msg to admin to login using $new_password
    //     $url = url('login');
    //     $url = $url . "?my_email=" . $model->admin_email;
    //     $url = $url . "&my_pass=" . $new_password;

    //     if ($nopd_admin != null) {
    //         $exist->password = $hashed_password;
    //         $exist->save();

    //         $body = <<<EOF
    //         Dear Sir/Madam,
    //         <br>
    //         <br>
    //         Your password has been reset. You can now login to the Persons with Disability Observatory using the following credentials:
    //         <br>
    //         <br><b>EMAIL:</b> {$model->admin_email}
    //         <br><b>PASSWORD:</b> {$new_password}
    //         <br>
    //         <br>
    //         <b>OR click the link below to login:</b>
    //         <br>
    //         <br><b>LINK:</b> {$url}
    //         <br>
    //         <br>
    //         Regards,
    //         <br>
    //         8Tech Team.
    //     EOF;

    //         $data = [
    //             'email' => $model->admin_email,
    //             'name' => $nopd_admin->name,
    //             'subject' => 'Password Reset - ' . env('APP_NAME') . date('Y-m-d H:i:s'),
    //             'body' => $body
    //         ];
    //         Utils::mail_send($data);
    //     }
    // }
}
