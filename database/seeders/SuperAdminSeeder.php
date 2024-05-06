<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'admin@laravel.com')->first();
        if (is_null($user)) {
            $user = new User;
            $user->name = 'Super Administrator';
            $user->email = 'admin@laravel.com';
            $user->password = bcrypt('admin123');
            $user->save();
        }

        $roles = [
            'administrator',
        ];

        foreach ($roles as $role) {
            $check = Role::where('name', $role)->first();
            if (!is_null($check)) {
                continue;
            }
            Role::create([
                'name'    => $role
            ]);
        }

        $user->assignRole('administrator');
    }
}
