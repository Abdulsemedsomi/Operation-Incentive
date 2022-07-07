<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ceo = Role::create([
            'name' => 'Admin',
            'slug' =>'admin',
            'permissions' => json_encode([
            'crud' => true,

            ]),
        ]);
        $manager = Role::create([
            'name' => 'Manager',
            'slug' =>'manager',
            'permissions' => json_encode([
            'manager' => true,

            ]),
        ]);
        $user = Role::create([
            'name' => 'User',
            'slug' =>'user',
            'permissions' => json_encode([

            'keyresult-crud' => true,
            'objective-crud' => true,

            ]),
        ]);
    }
}
