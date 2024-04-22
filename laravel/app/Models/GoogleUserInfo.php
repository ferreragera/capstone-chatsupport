<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleUserInfo extends Model
{
    use HasFactory;
    
    protected $table = 'google_userinfo';

    protected $fillable = ['gid', 'email', 'givenName', 'familyName', 'name', 'picture', 'verifiedEmail', 'hd'];
}
