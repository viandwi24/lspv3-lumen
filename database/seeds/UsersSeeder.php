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
            'role'      => 'Accession',
            'status'    => 'Active'
        ]);
        User::create([
            'name'      => 'Example Assessors 1',
            'email'     => 'assessor1@gmail.com',
            'username'  => 'assessor1',
            'password'  => Hash::make('password'),
            'role'      => 'Assessor',
            'status'    => 'Active'
        ]);
        User::create([
            'name'      => 'Example Assessors 2',
            'email'     => 'assessor2@gmail.com',
            'username'  => 'assessor2',
            'password'  => Hash::make('password'),
            'role'      => 'Assessor',
            'status'    => 'Active'
        ]);
        User::create([
            'name'      => 'Admin LSP',
            'email'     => 'admin@mail.com',
            'username'  => 'admin',
            'password'  => Hash::make('password'),
            'role'      => 'Admin',
            'status'    => 'Active'
        ]);
    }
}
