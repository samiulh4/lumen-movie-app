<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersAuthLog extends Model
{
    protected $table = 'users_auth_logs';
    protected $guarded = ['id'];
}

?>
