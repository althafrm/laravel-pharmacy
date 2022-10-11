<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'images',
        'note',
        'delivery_address',
        'delivery_time_from',
        'delivery_time_to',
        'has_quotation',
        'created_by',
        'updated_by',
    ];

    // Many-To-One relationship: belongsTo User
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Many-To-One relationship: belongsTo User
    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // One-To-One relationship: hasOne Quotation
    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
}
