<?php

namespace App\Imports;

use App\Role_user;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if($row[1] != null &&  $row[1] !="User Name"){


        return  User::updateOrCreate([
            'email'    => $row[11]],
            ['position'    => $row[3],
            'fname'     => explode(" ", $row[2])[0],
            'lname'     =>  explode(" ", $row[2])[1],
            'reportsTo'    => $row[13],
            'team'    => trim($row[7]), 
            'avatarcolor' => $this->doNewColor()
         ]);


        }


    }
    public function doNewColor(){
        $color = dechex(rand(0x000000, 0xFFFFFF));
        return $color;
}
}
