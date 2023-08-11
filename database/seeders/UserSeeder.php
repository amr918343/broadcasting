<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        User::create([
           'full_name' => 'user',
           'email' => 'user@gmail.com',
           'password' => Hash::make('password'),
           'phone_number'=>'1234567895',
           'created_at'=> now(),
           'updated_at'=> now(),
       ]);

    }
}
