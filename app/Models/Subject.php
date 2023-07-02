<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model {
    use HasFactory;
    protected $guarded = [];
    public function exams() {
        return $this->hasMany(Exam::class, 'subject_id', 'id');
    }
}
