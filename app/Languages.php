<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected $table="language";
    protected $primaryKey="id";
    public $timestamps = false;
}
