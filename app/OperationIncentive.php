<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationIncentive extends Model
{
    //
    protected $guarded = [];

    public function userss(){
        return $this->belongsToMany(User::class,'opration_incentive_user','task_id','user_id');
    }
}
