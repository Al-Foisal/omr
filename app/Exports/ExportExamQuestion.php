<?php

namespace App\Exports;

use App\Models\ExamQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExamQuestion implements FromCollection {
    protected $exam_id;
    protected $subject_id;
    protected $total_question;
    public function __construct($exam_id, $subject_id, $total_question) {
        $this->exam_id        = $exam_id;
        $this->subject_id     = $subject_id;
        $this->total_question = $total_question;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        return ExamQuestion::where('exam_id', $this->exam_id)
            ->where('subject_id', $this->subject_id)
            ->limit($this->total_question)
            ->select(['id', 'exam_id', 'subject_id', 'subject_topic_id', 'question_name', 'question_explanation', 'status'])
            ->get();
    }
}
