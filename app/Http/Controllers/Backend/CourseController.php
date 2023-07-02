<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Subject;
use App\Models\SubjectTopic;
use Illuminate\Http\Request;

class CourseController extends Controller {
    public function index() {
        $data = Course::latest()->paginate(100);

        return view('backend.course.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        if ($id) {
            $data    = Course::findOrFail($id);
            $subject = Subject::where('course_id', $data->id)->get();
        } else {
            $data    = null;
            $subject = null;
        }

        return view('backend.course.create-or-edit', compact('data', 'subject'));
    }

    public function storeOrUpdate(Request $request, $id = null) {
        $course = Course::updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'name'          => $request->name,
                'details'       => $request->details,
                'purchase_link' => $request->purchase_link,
            ]
        );

        foreach ($request->subject as $key => $subject) {

            if (empty($subject)) {
                continue;
            }

            if ($subject && $request->subject_id[$key] == null) {
                Subject::create([
                    'course_id' => $course->id,
                    'name'      => $subject,
                ]);
            } else {
                $update_subject       = Subject::find($request->subject_id[$key]);
                $update_subject->name = $subject;
                $update_subject->save();
            }

        }

        return to_route('admin.course.createOrUpdateExam', $course->id)->withToastSuccess('Course and subject update successfully!!');
    }

    public function createOrUpdateExam($id, $exam_id = null) {
        $data     = Course::findOrFail($id);
        $exam     = Exam::find($exam_id);
        $subject  = Subject::where('course_id', $data->id)->get();
        $all_exam = Exam::latest()->paginate(100);

        return view('backend.course.create-or-update-exam', compact('data', 'subject', 'exam', 'all_exam'));
    }

    public function storeOrUpdateExam(Request $request) {
        $check_exam = Exam::where('course_id', $request->course_id)
            ->where('subject_id', $request->subject_id)
            ->where('name', $request->name)
            ->first();

        if (!$request->exam_id && $check_exam) {
            return back()->withToastError('Same course, subject and exam name added before.');
        }
        

        $before_update = null;

        if ($request->exam_id) {

            $before_update = Exam::find($request->exam_id);
        }

        $exam = Exam::updateOrCreate(
            [
                'id' => $request->exam_id,
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

        foreach ($request->topic as $key => $topic) {

            if (empty($topic)) {
                continue;
            }

            if ($topic && $request->topic_id[$key] == null) {
                SubjectTopic::create([
                    'exam_id' => $exam->id,
                    'name'    => $topic,
                ]);
            } else {
                $update_topic       = SubjectTopic::find($request->topic_id[$key]);
                $update_topic->name = $topic;
                $update_topic->save();
            }

        }

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

        return to_route('admin.course.createOrUpdateExam', $exam->course_id)->withToastSuccess('Exam added successfully');

    }

    public function updateStatus(Request $request) {
        $data         = Course::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.course.index')->withToastSuccess('Status updated successfully!!');
    }

    public function updateCourseSubjectStatus($id) {
        $subject         = Subject::find($id);
        $subject->status = $subject->status == 1 ? 0 : 1;
        $subject->save();

        return back()->withToastSuccess('Status updated successfully!!');
    }

    public function updateCourseSubjectTopicStatus($id) {
        $subject         = SubjectTopic::find($id);
        $subject->status = $subject->status == 1 ? 0 : 1;
        $subject->save();

        return back()->withToastSuccess('Status updated successfully!!');
    }

}
