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

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function getSubjectTopicDetailsAttribute() {
        return SubjectTopic::whereIn('id', explode(',', $this->subject_topic_id))->get();
    }
}
