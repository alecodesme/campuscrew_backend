<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'country',
        'city',
        'province',
        'email',
        'cellphone',
        'user_id',
        'domain',
    ];

    public function clubs()
    {
        return $this->hasMany(Club::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
