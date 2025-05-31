<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client',
        'task_bug_name',
        'owner',
        'priority',
        'start_date',
        'end_date',
        'dev_status',
        'unit_test_status',
        'staging_status',
        'prod_status',
        'comment',
        'user_id',
    ];

    // relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
