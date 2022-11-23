<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    /**
     * user_from
     *
     * @return void
     */
    public function user_from()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    /**
     * user_to
     *
     * @return void
     */
    public function user_to()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }
}
