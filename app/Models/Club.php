<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'university_id',
        'created_by',
        'email',
        'is_active',
        'tags',
    ];

    // Relación con la universidad
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    // Relación con el usuario creador
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
