<?php

namespace App\Models;

use Encore\Admin\Form\Field\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as RelationsBelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\Models\AdminRole;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form\Field\BelongsTo;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;

class User extends Administrator implements JWTSubject, AuthenticatableContract, CanResetPasswordContract
{
    use HasFactory;
    use Notifiable;
    use AuthenticableTrait, CanResetPassword;


     public function setPasswordAttribute($value)
    {
        // If the value is already a Bcrypt hash, leave it
        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
    //boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            //check_default_organisation
            Utils::check_default_organisation();
        });
    }

    protected $fillable = [
        'email', 'password', 'token','username'
    ];

    public function profile()
    {
        return $this->hasOne(Person::class, 'user_id');
    }

    protected $guarded = [];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function programs()
    {
        return $this->hasMany(UserHasProgram::class, 'user_id');
    }

    public function managedOrganisation()
    {
        //belong to organisation
        return $this->belongsTo(Organisation::class);
    }


    public function service_provider()
    {
        return $this->hasOne(ServiceProvider::class, 'user_id');
    }

    public function assignRole(String $role)
    {
        $role = AdminRole::where('slug', $role)->first();
        FacadesDB::table('admin_role_users')->insert([
            'role_id' => $role->id,
            'user_id' => $this->id
        ]);
    }

    //user belongs to organisation
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public static function updateUserOrganisationId()
    {
        // Get users with organisation_id set to 0
        $usersToUpdate = self::where('organisation_id', 0)->get();

        foreach ($usersToUpdate as $user) {
            // Get the corresponding organisation_id from the Organisation table
            $organisationId = Organisation::where('user_id', $user->id)->value('id');

            if (!is_null($organisationId)) {
                // Update the user's organisation_id
                self::where('id', $user->id)
                    ->update(['organisation_id' => $organisationId]);
            }
        }


        return count($usersToUpdate);
    }

    public function sendActivationEmail($activation_token)
    {
        $activation_url = url('activate') . "?email=" . $this->email . "&token=" . $activation_token;

        $body = <<<EOF
            Hello,
            <br><br>
            Welcome to <strong>Inclusive ICT Observatory</strong>! To complete your registration and activate your account, please verify your email address by clicking the link below:
            <br><br>
            <b>EMAIL:</b> {$this->email}
            <br><br>
            <b>ACTIVATION LINK:</b> <a href="{$activation_url}">Activate Your Account</a>
            <br><br>
            If you did not create an account, please ignore this email.
            <br><br>
            Regards,
            <br>
            EOF;

        $data = [
            'email' => $this->email,
            'name' => $this->name,
            'subject' => 'Account activation - ' . env('APP_NAME') . date('Y-m-d H:i:s'),
            'body' => $body
        ];
        Utils::mail_send($data);
    }

    public function getProfilePic(){
        if(!empty($this->profile_photo) && file_exists('/storage/images/'.$this->profile_photo))
        {
            return url('/storage/images/'.$this->profile_photo);
        }else{
            return url('/chat/logo/user.webp');
        }
    }

    
}
