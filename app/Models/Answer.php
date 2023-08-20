<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }
}
