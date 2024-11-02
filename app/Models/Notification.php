<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const IS_VIEW_UNVIEW = 1;
    const IS_VIEW_VIEWED = 2;
    const STATUS_WAIT = 'wait';
    const STATS_DONE = 'done';
    use HasFactory;

    protected $table = "notifications";

    protected $fillable = [
        'user_id', 'action_id', 'content', 'is_view',
        'status'
    ];
}
