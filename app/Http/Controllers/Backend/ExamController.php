<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller {
    public function index() {
        $data = Exam::latest()->paginate(100);

        return view('backend.exam.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data       = Exam::findOrFail($id);
            $get_course = Course::find($data->course_id);
            $subject    = Subject::whereIn('id', explode(',', $get_course->subject_id))->where('status', 1)->orderBy('name')->get();
        } else {
            $data    = null;
            $subject = null;
        }

        $course = Course::where('status', 1)->orderBy('name')->get();

        return view('backend.exam.create-or-edit', compact('data', 'course', 'subject'));
    }

    public function storeOrUpdate(Request $request, $id = null) {
        $before_update = null;

        if ($id) {
            $before_update = Exam::find($id);
        }

        $exam = Exam::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'course_id'                  => $before_update ? $before_update->course_id : $request->course_id,
                'subject_id'                 => $before_update ? $before_update->subject_id : $request->subject_id,
                'name'                       => $request->name,
                'total_question'             => $request->total_question,
                'per_question_positive_mark' => $request->per_question_positive_mark,
                'per_question_negative_mark' => $request->per_question_negative_mark,
            ]
        );

        if ($before_update) {

            /**
             * updating question row for every subjects
             */
            $check_question = ExamQuestion::where('exam_id', $exam->id)
                ->where('subject_id', $exam->subject_id)
                ->count();

            if ($exam->total_question > $check_question) {
                $next_addition = $exam->total_question - $check_question;

                for ($i = 1; $i <= $next_addition; $i++) {
                    ExamQuestion::create([
                        'exam_id'    => $exam->id,
                        'subject_id' => $exam->subject_id,
                    ]);
                }

            }

        } else {

            /**
             * creating new question row when creating new course exam
             */

            for ($i = 1; $i <= $request->total_question; $i++) {
                ExamQuestion::create([
                    'exam_id'    => $exam->id,
                    'subject_id' => $exam->subject_id,
                ]);
            }

        }

        return to_route('admin.exam.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = Exam::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.exam.index')->withToastSuccess('Status updated successfully!!');
    }

    public function getCourseWiseSubject(Request $request) {
        $course = Course::find($request->course_id);
        $data   = Subject::whereIn('id', explode(',', $course->subject_id))->where('status', 1)->orderBy('name')->get();

        return json_encode($data);
    }

}
