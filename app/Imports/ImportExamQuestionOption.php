<?php

namespace App\Imports;

use App\Models\ExamQuestionOption;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportExamQuestionOption implements ToModel {

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        $options = explode('--', $row[7]);
        $answer  = explode('--', $row[8]);

        if (!empty($row[7])) {

            foreach ($options as $key => $o_item) {
                ExamQuestionOption::create([
                    'exam_question_id' => $row[0],
                    'option'           => $o_item,
                    'is_answer'        => $answer[$key],
                ]);

            }

        }

    }

}
