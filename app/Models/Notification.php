<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    use HasFactory;
    protected $guarded = [];
    public function getTypesAttribute() {
        $type = $this->type;

        if ($type == 1) {
            return 'All Student';
        } elseif ($type == 2) {
            return 'Individual';
        } else {
            return 'Course Wise';
        }

    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

}
