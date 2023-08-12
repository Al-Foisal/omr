<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    use HasFactory;
    protected $guarded = [];
    public function subjects() {
        return $this->hasMany(Subject::class);
    }

    public function enrolled() {
        return $this->hasMany(CourseRegistration::class);
    }

    public function exams() {
        return $this->hasManyThrough(Exam::class, Subject::class);
    }
}
