<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    // to create relationship between Entry and User
    // $entry->user
    // Entry N - 1 User
    // Eager Loading

    public function user(){
        return $this->belongsTo( User::class );
    }
}
