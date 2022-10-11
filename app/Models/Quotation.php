<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'quotation_detail',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quotation_detail' => 'array',
    ];

    public const PENDING = 'PENDING';
    public const APPROVED = 'APPROVED';
    public const REJECTED = 'REJECTED';
    public const DELIVERED = 'DELIVERED';

    // Many-To-One relationship: belongsTo User
    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // One-To-One relationship: belongsTo Prescription
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
