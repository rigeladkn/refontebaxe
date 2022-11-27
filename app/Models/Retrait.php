<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retrait extends Model
{
    use HasFactory;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    public function distributeur()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }
}
