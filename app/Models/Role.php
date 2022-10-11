<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const ADMIN = 1;
    public const PHARMACY = 2;
    public const USER = 3;

    // One-To-Many relationship: hasMany User
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
