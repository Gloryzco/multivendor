<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // User::factory()->count(1)->create()->each(function ($user){
        //     $user->assignRole('superadmin');
        // });
        User::factory()->count(2)->create()->each(function ($user){
            $user->assignRole('admin');
        });
        User::factory()->count(5)->create()->each(function ($user){
            $user->assignRole('vendor');
        });
        User::factory()->count(5)->create()->each(function ($user){
            $user->assignRole('customer');
        });

        $user = User::create([
            'email' => "andrewglory32@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make("1234"), // password
            'remember_token' => Str::random(10),
        ]);

        $user -> assignRole('superadmin');
        return $user;
    }
}
