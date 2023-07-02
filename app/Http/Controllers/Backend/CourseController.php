<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
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

        return back()->withToastSuccess('Data update successfully!!');
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

        return back()->withToastSuccess('Subject status updated successfully!!');
    }

}
