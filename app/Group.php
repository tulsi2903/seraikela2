<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table="scheme_group";
    protected $primaryKey="scheme_group_id";
    public $timestamps = false;

}
