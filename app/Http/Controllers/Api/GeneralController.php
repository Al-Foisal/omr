<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller {
    public function registeredOrSuggestCourses() {
        $data   = [];
        $course = CourseRegistration::where('user_id', auth()->user()->id)
        // ->where('status', 1)
            ->orderBy('id', 'desc')
            ->pluck('course_id')
            ->toArray();

        $registered_courses         = [];
        $registered_courses_details = Course::whereIn('id', $course)->get();

        foreach ($registered_courses_details as $key => $r_details) {
            $registered_courses[]                 = $r_details;
            $registered_courses[$key]['subjects'] = Subject::whereIn('id', explode(',', $r_details->subject_id))->get();
        }

        $suggested_courses         = [];
        $suggested_courses_details = Course::whereNotIn('id', $course)->get();

        foreach ($suggested_courses_details as $s_key => $s_details) {
            $suggested_courses[]                   = $s_details;
            $suggested_courses[$s_key]['subjects'] = Subject::whereIn('id', explode(',', $s_details->subject_id))->get();
        }

        $data['registered_courses'] = $registered_courses;
        $data['suggested_courses']  = $suggested_courses;

        return $this->successMessage('ok', $data);
    }

    public function storeCourseRegistration(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->validationMessage($validator->errors());
        }

        if (
            !CourseRegistration::where('user_id', auth()->user()->id)
            ->where('course_id', $request->course_id)
            ->where('order_id', $request->order_id)
            ->exists()
        ) {
            CourseRegistration::create([
                'user_id'   => auth()->user()->id,
                'course_id' => $request->course_id,
                'order_id'  => $request->order_id,
            ]);

            return $this->successMessage('Course registration successful! Wait for admin approval');
        } else {
            $this->errorMessage('You have registered this course before!');
        }

    }

}
