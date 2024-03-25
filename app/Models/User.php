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
use Illuminate\Support\Facades\DB as FacadesDB;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

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
        return $this->hasOne(Organisation::class, 'user_id');
    }


    public function service_provider()
    {
        return $this->hasOne(ServiceProvider::class, 'user_id');
    }

    public function assignRole(String $role)
    {
        $role = AdminRole::where('slug', $role)->first();
        DB::table('admin_role_users')->insert([
            'role_id' => $role->id,
            'user_id' => $this->id
        ]);
    }
}
