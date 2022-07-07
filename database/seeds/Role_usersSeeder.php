<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Role_usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('role_users')->insert([
            'user_id' => User::where('email', 'apply@ienetworksolutons.com')->first()->id,
            'role_id' => Role::where('name', 'Admin')->first()->id,

        ]);
    }
}
