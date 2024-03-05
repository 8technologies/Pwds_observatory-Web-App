<?php

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

if(!function_exists('assignRole')) {
    function assignRole($user, $role_slug, $reassign = false) : bool
    {
        $role = DB::table('admin_roles')->where('slug', $role_slug)->first();
        if ($role) {
            if($reassign) {
                DB::update('update admin_role_users set role_id = ? where user_id = ?', [$role->id, $user->id]);
            }else {
                DB::insert('insert into admin_role_users (role_id, user_id) values (?,?)', [$role->id, $user->id]);
            }
            return true;
        }
        return false;
    }
}

if(!function_exists('removeRole')) {
    function removeRole($user, $role_slug) : bool
    {
        $role = DB::table('admin_roles')->where('slug', $role_slug)->first();
        if ($role) {
            DB::delete('delete from admin_role_users where role_id = ? and user_id = ?', [$role->id, $user->id]);
            return true;
        }
        return false;
    }
}