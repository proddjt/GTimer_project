<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = ['time', 'user_id', 'puzzle', 'date', 'scramble', 'hasPenalty', 'hasDNF'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
