<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'start_time', 'end_time', 'min_volunteers', 'max_volunteers'];

    // La relation avec les utilisateurs
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}