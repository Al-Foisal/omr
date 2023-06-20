<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model {
    use HasFactory;
    protected $guarded = [];
    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function getSubjectDetailsAttribute() {
        return Subject::whereIn('id', explode(',', $this->subject_id))->get();
    }

    public function getSubjectTopicDetailsAttribute() {
        return SubjectTopic::whereIn('id', explode(',', $this->subject_topic_id))->get();
    }
}
