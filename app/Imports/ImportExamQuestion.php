<?php

namespace App\Imports;

use App\Models\ExamQuestion;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportExamQuestion implements ToModel {
    protected $exam_id;
    protected $subject_id;
    public function __construct($exam_id, $subject_id) {
        $this->exam_id    = $exam_id;
        $this->subject_id = $subject_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        return new ExamQuestion([
            'exam_id'              => $this->exam_id,
            'subject_id'           => $this->subject_id,
            'subject_topic_id'     => $row[3],
            'question_name'        => $row[4],
            'question_explanation' => $row[5],
            'status'               => $row[6],
        ]);

    }

}
