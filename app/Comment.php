<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Xetaio\Mentions\Models\Traits\HasMentionsTrait;

class Comment extends Model
{
    //
    use HasMentionsTrait;
    protected $guarded = [];
    
}
