<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Alfian Dwi Nugraha',
            'email'     => 'viandwicyber@gmail.com',
            'username'  => 'viandwi24',
            'password'  => Hash::make('password'),
            'role'      => 'Asesi',
            'status'    => 'Active'
        ]);
    }
}
