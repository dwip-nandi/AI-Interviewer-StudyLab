<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * We changed it from 'interview_setting-ms' to 'interview_settings'
     */
    protected $table = 'interview_settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['guideline', 'sector'];
}