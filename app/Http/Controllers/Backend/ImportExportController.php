<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportExamQuestion;
use App\Http\Controllers\Controller;
use App\Imports\ImportExamQuestion;
use App\Imports\ImportExamQuestionOption;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller {
    public function export(Request $request) {
        return Excel::download(new ExportExamQuestion($request->exam_id, $request->subject_id, $request->total_question), 'exam-question.xlsx');
    }

    public function import(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $question = ExamQuestion::where('exam_id', $request->exam_id)->where('subject_id', $request->subject_id)->get();

        foreach ($question as $item) {

            if ($item->examQuestionOptions) {

                foreach ($item->examQuestionOptions as $option) {

                    if ($option) {
                        $option->delete();
                    }

                }

            }

            $item->delete();
        }

        Excel::import(new ImportExamQuestion($request->exam_id, $request->subject_id), request()->file('file'));

        return back();

    }

    public function importAnswer(Request $request) {
        $validator = Validator::make($request->all(), [
            'answerfile' => 'mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        Excel::import(new ImportExamQuestionOption(), request()->file('answerfile'));

        return back();

    }

}
