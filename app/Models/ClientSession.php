<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSession extends Model
{
    public $table = "bt_client_session";
    protected $attributes = [
        'closed_at' => null
    ];
    use HasFactory;
}
