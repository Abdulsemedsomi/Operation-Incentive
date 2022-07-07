<?php

use App\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ceo = Team::create([
            'team_name' => 'HCS',
            'parentteam' => 'CEO'

        ]);
    }
}
