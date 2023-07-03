<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\User;

class DashboardController extends Controller {
    public function dashboard() {
        $data                       = [];
        $data['total_courses']      = Course::count();
        $data['total_students']     = User::count();
        $data['total_registration'] = CourseRegistration::where('status', 0)->count();
        $data['total_month_enroll'] = CourseRegistration::where('status', 1)->whereMonth('created_at', date("Y-m-d"))->count();
        $data['total_year_enroll']  = CourseRegistration::where('status', 1)->whereYear('created_at', date("Y-m-d"))->count();
        $data['total_enroll']       = CourseRegistration::where('status', 1)->count();

        return view('backend.dashboard', $data);
    }
}
