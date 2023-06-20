<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
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
        Exam::updateOrCreate(
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

        return to_route('admin.exam.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = Exam::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.exam.index')->withToastSuccess('Status updated successfully!!');
    }

    public function getSubjectWiseTopic(Request $request) {
        $data = SubjectTopic::whereIn('subject_id', $request->subject_id)->where('status', 1)->orderBy('name')->get();

        return json_encode($data);
    }

}
