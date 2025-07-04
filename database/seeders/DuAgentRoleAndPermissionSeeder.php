<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Role;

class DuAgentRoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // 1) Find or create the DU-Agent role
        $duAgent = Role::firstOrCreate(
            ['slug' => 'du-agent'],
            ['name' => 'DU Agent']
        );

        // 2) Fetch the district-union role & its permission IDs
        $duUnion = Role::where('slug', 'district-union')->first();

        if (! $duUnion) {
            $this->command->error("district-union role not found!");
            return;
        }

        $permIds = $duUnion->permissions()->pluck('id')->toArray();

        // 3) Mirror exactly that permission set onto du-agent
        $duAgent->permissions()->sync($permIds);

        $this->command->info("Synced du-agent perms to match district-union (".count($permIds)." total).");
    }
}
