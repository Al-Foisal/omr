<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CourseRegistration;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class AdminAuthController extends Controller
{

    public function sms_login()
    {


//        return Http::acceptJson()->post(
//            'https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2',
//            [
//                "username" => "Bondiadmin",
//                "password" => "Emon0561@@@",
//                "apicode" => "1",
//                "msisdn" => "01770744894",
//                "countrycode" => "880",
//                "cli" => "2222",
//                "messagetype" => "1",
//                "message" => "SingleSMS_JesonTest1",
//                "messageid" => "0",
//            ]
//        );

        $number = '01770744894';
        $body = 'Test sms';
        $url = "https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2";
        $data = [
            "username" => "Bondiadmin",
            "password" => "Emon0561@@@",
            "apicode" => "1",
            "msisdn" => $number,
            "countrycode" => "880",
            "cli" => "2222",
            "messagetype" => "1",
            "message" => $body,
            "messageid" => "0",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function login()
    {

//        return Http::post('https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2', [
//            "username" => "Bondiadmin",
//            "password" => "Emon0561@@@",
//            "apicode" => "1",
//            "msisdn" => "01770744894",
//            "countrycode" => "880",
//            "cli" => "2222",
//            "messagetype" => "1",
//            "message" => "SingleSMS_JesonTest1",
//            "messageid" => "0",
//
//        ]);

        return view('backend.auth.login');
    }

    public function storeLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {

            return redirect()->route('admin.dashboard');

        }

        return redirect()->route('admin.auth.login')->withToastError('Invalid Credentitials!!');

    }

    public function adminList()
    {
        $admins = Admin::all();

        return view('backend.auth.admin-list', compact('admins'));
    }

    public function customerList()
    {
        $customers = User::orderBy('id', 'desc')->withCount([
            'courses' => function ($query) {
                return $query->where('status', 1);
            },
            'exams',
        ])->paginate(500);

        return view('backend.auth.customer-list', compact('customers'));
    }

    public function createAdmin()
    {
        return view('backend.auth.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:admins',
            'password' => 'required',
            'address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        if ($request->hasFile('image')) {

            $image_file = $request->file('image');

            if ($image_file) {

                $img_gen = hexdec(uniqid());
                $image_url = 'images/admin/';
                $image_ext = strtolower($image_file->getClientOriginalExtension());

                $img_name = $img_gen . '.' . $image_ext;
                $final_name1 = $image_url . $img_gen . '.' . $image_ext;

                $image_file->move($image_url, $img_name);
            }

        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->address = $request->address;
        $admin->image = $final_name1 ?? '';
        $admin->users = $request->users;
        $admin->course = $request->course;
        $admin->exam = $request->exam;
        $admin->general = $request->general;
        $admin->status = 1;
        $admin->save();

        return redirect()->route('admin.auth.adminList')->withToastSuccess('New Amin Registered Successfully!!');
    }

    public function editAdmin(Admin $admin)
    {
        return view('backend.auth.edit-admin', compact('admin'));
    }

    public function updateAdmin(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        if ($request->hasFile('image')) {

            $image_file = $request->file('image');

            if ($image_file) {

                $image_path = public_path($admin->image);

                if (File::exists($image_path)) {
                    File::delete($image_path);
                }

                $img_gen = hexdec(uniqid());
                $image_url = 'images/admin/';
                $image_ext = strtolower($image_file->getClientOriginalExtension());

                $img_name = $img_gen . '.' . $image_ext;
                $final_name1 = $image_url . $img_gen . '.' . $image_ext;

                $image_file->move($image_url, $img_name);
                $admin->image = $final_name1;
                $admin->save();

            }

        }

        $admin->name = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;
        $admin->address = $request->address;
        $admin->users = $request->users;
        $admin->course = $request->course;
        $admin->exam = $request->exam;
        $admin->general = $request->general;
        $admin->save();

        return redirect()->route('admin.auth.adminList')->withToastSuccess('The admin updated successfully!!');
    }

    public function activeAdmin(Request $request, Admin $admin)
    {
        $admin->status = 1;
        $admin->save();

        return redirect()->back()->withToastSuccess('The admin activated successfully!!');
    }

    public function inactiveAdmin(Request $request, Admin $admin)
    {
        $admin->status = 0;
        $admin->save();

        return redirect()->back()->withToastSuccess('The admin inactivated successfully!!');
    }

    public function deleteAdmin(Request $request, Admin $admin)
    {
        $image_path = public_path($admin->image);

        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        $admin->delete();

        return redirect()->back()->withToastSuccess('The admin deleted successfully!!');
    }

    public function updateStatus(Request $request)
    {
        $data = User::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return back()->withToastSuccess('Status updated successfully!!');
    }

    public function studentDetails($id)
    {
        $data = [];
        $data['user'] = user::findOrFail($id);
        $registered_courses = CourseRegistration::where('user_id', $id)->where('status', 1)->pluck('course_id')->toArray();
        $data['exam'] = Exam::whereIn('course_id', $registered_courses)->get();

        return view('backend.auth.student-details', $data);
    }

    public function studentCourseDetails($user_id, $course_id)
    {
        $data = [];
        $data['user'] = user::findOrFail($user_id);
        $registered_courses = CourseRegistration::where('user_id', $user_id)->where('course_id', $course_id)->where('status', 1)->pluck('course_id')->toArray();
        $data['exam'] = Exam::whereIn('course_id', $registered_courses)->with('answer')->get();

        return view('backend.auth.student-course-details', $data);
    }

    public function studentDelete($id)
    {
        $user = User::findOrFail($id);

        $user->courses()->delete();
        $user->exams()->delete();
        $user->notification()->delete();

        $user->delete();

        return to_route('admin.auth.customerList')->withToastSuccess('Student deleted successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        return redirect()
            ->route('admin.auth.login')
            ->withToastSuccess('Logout Successful!!');
    }

}
