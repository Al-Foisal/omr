<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model {
    use HasFactory;
    protected $guarded = [];
    public function subjectToics() {
        return $this->hasMany(SubjectTopic::class);
    }
}
