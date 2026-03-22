<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pr_header';
    protected $primaryKey = 'pr_id';

    protected $fillable = [
        'pr_number',
        'requester_id',
        'department_id',
        'request_date',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'request_date' => 'date',
        'approved_at'  => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by', 'user_id');
    }

    public function lines()
    {
        return $this->hasMany(PurchaseRequestLine::class, 'pr_id', 'pr_id');
    }
}