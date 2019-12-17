<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheakLogout extends Model
{
    public $timestamps="false";
    public $table = "logoutcheck";

    protected $primaryKey = 'id';
}
