<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    protected $guarded = [];
    protected $attributes = [
        'status' => 'Active',
    ];

}
