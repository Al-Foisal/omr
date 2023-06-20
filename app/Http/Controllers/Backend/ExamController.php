<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Subject;
use App\Models\SubjectTopic;
use Illuminate\Http\Request;

class ExamController extends Controller {
    public function index() {
        $data = Exam::orderBy('name')->get();

        return view('backend.exam.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data       = Exam::findOrFail($id);
            $data_topic = SubjectTopic::whereIn('subject_id', explode(',', $data->subject_id))->where('status', 1)->orderBy('name')->get();
        } else {
            $data       = null;
            $data_topic = null;
        }

        $course  = Course::where('status', 1)->orderBy('name')->get();
        $subject = Subject::where('status', 1)->orderBy('name')->get();

        return view('backend.exam.create-or-edit', compact('data', 'course', 'subject', 'data_topic'));
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
                'course_id'                  => $request->course_id,
                'subject_id'                 => implode(',', $request->subject_id),
                'subject_topic_id'           => implode(',', $request->subject_topic_id),
                'name'                       => $request->name,
                'total_question'             => $request->total_question,
                'per_question_positive_mark' => $request->per_question_positive_mark,
                'per_question_negative_mark' => $request->per_question_negative_mark,
            ]
        );

        if ($before_update) {

            foreach (explode(',', $exam->subject_id) as $subject) {
                /**
                 * updating question row for every subjects
                 */
                $check_question = ExamQuestion::where('exam_id', $exam->id)
                    ->where('subject_id', $subject)
                    ->count();

                if ($exam->total_question > $check_question) {
                    $next_addition = $exam->total_question - $check_question;

                    for ($i = 1; $i <= $next_addition; $i++) {
                        ExamQuestion::create([
                            'exam_id'    => $exam->id,
                            'subject_id' => $subject,
                        ]);
                    }

                }

            }

            /**
             * deleting question row that was selected before update but not selected after update
             */
            $delete_question = ExamQuestion::where('exam_id', $exam->id)
                ->whereNotIn('subject_id', explode(',', $exam->subject_id))
                ->delete();

        } else {

            /**
             * creating new question row when creating new course exam
             */

            foreach (explode(',', $exam->subject_id) as $subject) {

                for ($i = 1; $i <= $request->total_question; $i++) {
                    ExamQuestion::create([
                        'exam_id'    => $exam->id,
                        'subject_id' => $subject,
                    ]);
                }

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

    public function getSubjectWiseTopic(Request $request) {
        $data = SubjectTopic::whereIn('subject_id', $request->subject_id)->where('status', 1)->with('subject')->orderBy('name')->get();

        return json_encode($data);
    }

}
