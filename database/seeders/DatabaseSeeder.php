<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=> now()
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=> now()
            ],
            [
                'name'=> 'User',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=> now()
            ]
        ]);

        $user = User::create([
             'first_name' =>'Mr',
             'last_name' =>' Admin',
             'name' => 'Mr Admin',
             'status' => 1,
             'tag_id' => null,
             'avatar' => null,
             'email' => 'admin@admin.com',
             'password' => Hash::make('adminadmin'),
            'created_at' => now(),
            'updated_at' => now()
         ]);
        $user->assignRole(1);
    }
}
