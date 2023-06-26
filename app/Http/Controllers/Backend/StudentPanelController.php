<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;

class StudentPanelController extends Controller {
    public function pendingCourseRegistration() {
        $data = CourseRegistration::where('status', 0)->latest()->paginate(100);

        return view('backend.student-panel.pending-course-registration', compact('data'));
    }
    public function approvedCourseRegistration() {
        $data = CourseRegistration::where('status', 1)->latest()->paginate(100);

        return view('backend.student-panel.approved-course-registration', compact('data'));
    }

    public function updateStatus(Request $request) {
        $data         = CourseRegistration::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return back()->withToastSuccess('Status updated successfully!!');
    }

    public function delete($id) {
        $data = CourseRegistration::findOrFail($id);
        $data->delete();

        return back()->withToastSuccess('Course request deleted successfully!!');
    }
}
