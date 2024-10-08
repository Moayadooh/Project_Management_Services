<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Junction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'task_id'];
}
