<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\SubjectTopic;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller {
    public function index() {
        $data = Exam::latest()->paginate(100);

        return view('backend.exam-question.index', compact('data'));
    }

    public function createOrEdit(Request $request, $id) {

        $exam          = Exam::find($id);
        $subject_topic = SubjectTopic::where('exam_id', $exam->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        $exam_question = ExamQuestion::where('exam_id', $exam->id)->limit($exam->total_question)->get();

        return view('backend.exam-question.create-or-edit', compact('exam', 'subject_topic', 'exam_question'));
    }

    public function storeOrUpdate(Request $request) {

        // dd($request->all());
        $exam_question = ExamQuestion::find($request->exam_question_id);

        if (!$exam_question) {
            return response()->json(['status' => false]);
        }

        $exam_question->question_name        = $request->question_name;
        $exam_question->subject_topic_id     = $request->subject_topic_id;
        $exam_question->question_explanation = $request->question_explanation;
        $exam_question->status               = 1;
        $exam_question->save();

        /**
         * delete previous options
         */
        $previous_options = ExamQuestionOption::where('exam_question_id', $exam_question->id)->delete();

        foreach ($request->options as $key => $option) {
            ExamQuestionOption::create([
                'exam_question_id' => $exam_question->id,
                'option'           => $option,
                'is_answer'        => $request->is_answer[$key],
            ]);
        }

        return response()->json(['status' => true]);

    }

    public function makeForReview(Request $request) {
        $exam_question = ExamQuestion::find($request->exam_question_id);

        if (!$exam_question) {
            return response()->json(['status' => false]);
        }

        $exam_question->status = 0;
        $exam_question->save();

        return response()->json(['status' => true]);
    }

    public function updateStatus(Request $request) {
        $data         = Exam::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.examQuestion.index')->withToastSuccess('Status updated successfully!!');
    }

    public function preview(Request $request) {
        $data = ExamQuestion::where('exam_id', $request->exam_id)
            ->where('subject_id', $request->exam_subject_id)
            ->limit($request->total_question)
            ->with('examQuestionOptions')
            ->get();
        $exam = Exam::find($request->exam_id);

        return view('backend.exam-question.preview', compact('data', 'exam'));
    }

    public function previewAnswer(Request $request) {
        $data = ExamQuestion::where('exam_id', $request->exam_id)
            ->where('subject_id', $request->exam_subject_id)
            ->limit($request->total_question)
            ->with('examQuestionOptions')
            ->get();
        $exam = Exam::find($request->exam_id);

        return view('backend.exam-question.preview-answer', compact('data', 'exam'));
    }

}
