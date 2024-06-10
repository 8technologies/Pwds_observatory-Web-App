<?php

namespace App\Models;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'location',
        'description',
        'minimum_academic_qualification',
        'required_experience',
        'photo',
        'how_to_apply',
        'hiring_firm',
        'deadline',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model = self::calculateDeadlineDays($model);
            $model = self::checkIfExpired($model);
            return $model;
        });

        // Define the updating event listener
        static::updating(function ($job) {
            $auth_user = Admin::user();
            if ($job->user_id !== $auth_user->id) {
                throw new \Exception("You are not authorized to update this job.");
            }
            $model = self::calculateDeadlineDays($job);
            $model = self::checkIfExpired($job);
            return $model;
        });
    }

    protected static function calculateDeadlineDays($model)
    {
        $deadline = new \DateTime($model->deadline);
        $today = new \DateTime();
        $interval = $today->diff($deadline);
        $model->days_remaining = $interval->format('%r%a'); // %r to include a minus sign if negative
        return $model;
    }

    // Function to check if the job post is expired
    protected static function checkIfExpired($model)
    {
        $deadline = new \DateTime($model->deadline);
        $today = new \DateTime();
        if ($today > $deadline) {
            $model->status = 'Expired';
        } else {
            $model->status = 'Active';
        }
        return $model;
    }
}
