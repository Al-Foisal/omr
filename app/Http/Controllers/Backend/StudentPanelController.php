<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CourseRegistration;
use App\Models\Notification;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;

class StudentPanelController extends Controller
{
    public function pendingCourseRegistration()
    {
        $data = CourseRegistration::where('status', 0)->latest()->paginate(100);

        return view('backend.student-panel.pending-course-registration', compact('data'));
    }

    public function approvedCourseRegistration()
    {
        $data = CourseRegistration::where('status', 1)->latest()->paginate(100);

        return view('backend.student-panel.approved-course-registration', compact('data'));
    }

    public function updateStatus(Request $request)
    {
        $data = CourseRegistration::findOrFail($request->id);

        $last_user_course_id = 1;
        $last_data = CourseRegistration::where('course_id', $data->course_id)->whereNotNull('user_course_id')->latest('updated_at')->first();

        if (isset($last_data) && $last_data->user_course_id != null) {
            $last_user_course_id = (int)$last_data->user_course_id + 1;
        }

        $data->user_course_id = str_pad((int)$last_user_course_id, 6, "0", STR_PAD_LEFT);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        $user = $data->user;

        if (isset($user->fcm_token)) {

            FCMService::send(
                $user->fcm_token,
                [
                    'title' => "Course enroll notice",
                    'body' => "Your course '" . $data->course->name . "' is approved by admin",
                ]
            );

            Notification::create([
                'name' => 'Course enroll notice',
                'details' => "Your course '" . $data->course->name . "' is approved by admin",
                'user_id' => $data->user_id,
                'course_id' => null,
                'type' => 2,
            ]);
        }

        return back()->withToastSuccess('Status updated successfully!!');
    }

    public function delete($id)
    {
        $data = CourseRegistration::findOrFail($id);

        $user = User::find($data->user_id);

        if (isset($user->fcm_token)) {
            FCMService::send(
                $user->fcm_token,
                [
                    'title' => "Course enroll notice",
                    'body' => "Your course '" . $data->course->name . "' is declined by admin",
                ]
            );

            Notification::create([
                'name' => 'Course enroll notice',
                'details' => "Your course '" . $data->course->name . "' is declined by admin",
                'user_id' => $data->user_id,
                'course_id' => null,
                'type' => 2,
            ]);
        }

        $data->delete();

        return back()->withToastSuccess('Course request deleted successfully!!');
    }

}
