<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'apply@ienetworksolutons.com',
            'team' => 'HCS'
        ]);
    }
}
