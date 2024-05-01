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

class User extends Administrator implements JWTSubject
{
    use HasFactory;
    use Notifiable;


    //boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            //check_default_organisation
            Utils::check_default_organisation();
        });
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
        // Get users with organisation_id set to 1
        $usersToUpdate = self::where('organisation_id', 1)->get();

        foreach ($usersToUpdate as $user) {
            // Get the corresponding organisation_id from the Organisation table
            $organisationId = Organisation::where('user_id', $user->id)->value('id');

            // Update the user's organisation_id
            self::where('id', $user->id)
                ->update(['organisation_id' => $organisationId]);
        }

        // Optional: Return the count of updated users or any other relevant information
        return count($usersToUpdate);
    }
}
