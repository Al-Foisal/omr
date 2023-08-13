<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller {
    public function store(Request $request) {
        DB::beginTransaction();

        try {

            if ($request['answer_input_image']) {

                $image_file         = base64_decode($request['answer_input_image']);
                $answer_input_image = '/images/answer/' . time() . '.' . 'png';
                $success            = file_put_contents(public_path() . $answer_input_image, $image_file);

            }

            if ($request['answer_output_image']) {

                $image_file          = base64_decode($request['answer_output_image']);
                $answer_output_image = '/images/answer/' . time() . '.' . 'png';
                $success             = file_put_contents(public_path() . $answer_output_image, $image_file);

            }

            $purchase                      = new Answer();
            $purchase->user_id             = auth()->user()->user_id;
            $purchase->exam_id             = $request->exam_id;
            $purchase->course_id           = $request->course_id;
            $purchase->subject_id          = $request->subject_id;
            $purchase->answer              = $request->answer;
            $purchase->answer_input_image  = $answer_input_image ?? null;
            $purchase->answer_output_image = $answer_output_image ?? null;
            $purchase->save();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Your answer has been submitted! Wait for verification',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $th,
            ]);
        }

    }

}
