<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller {
    public function index() {
        $data = Exam::orderBy('name')->get();

        return view('backend.exam-question.index', compact('data'));
    }

    public function createOrEdit($id = null) {

        return view('backend.exam-question.create-or-edit', compact('data', 'course', 'subject', 'data_topic'));
    }

    public function storeOrUpdate(Request $request, $id = null) {

        return to_route('admin.examQuestion.index')->withToastSuccess('Data update successfully!!');
    }

    public function updateStatus(Request $request) {
        $data         = Exam::findOrFail($request->id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        return to_route('admin.examQuestion.index')->withToastSuccess('Status updated successfully!!');
    }
}
