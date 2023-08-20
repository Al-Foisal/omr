<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller {
    public function store(Request $request) {
        DB::beginTransaction();

        try {

            if (Answer::where('user_id', $request->user_id)->where('exam_id', $request->exam_id)->exists()) {
                return $this->errorMessage('This answer has been taken before');
            }

            if ($request['answer_input_image']) {

                $image_file         = base64_decode($request['answer_input_image']);
                $answer_input_image = '/images/answer/' . time() . rand() . '.' . 'png';
                $success            = file_put_contents(public_path() . $answer_input_image, $image_file);

            }

            if ($request['answer_output_image']) {

                $image_file          = base64_decode($request['answer_output_image']);
                $answer_output_image = '/images/answer/' . time() . rand() . '.' . 'png';
                $success             = file_put_contents(public_path() . $answer_output_image, $image_file);

            }

            $purchase                      = new Answer();
            $purchase->user_id             = $request->user_id;
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

    public function show(Request $request) {
        $data = [];

        if (!Answer::where('user_id', $request->user_id)->where('exam_id', $request->exam_id)->exists()) {
            return $this->errorMessage('Somthing went wrong', '');
        }

        $answer = Answer::where('user_id', $request->user_id)
            ->where('exam_id', $request->exam_id)
            ->with(
                'user',
                'exam',
                'course',
                'subject'
            )
            ->first();

        $data['answer'] = $answer;

        $total_question  = (int) $answer->exam->total_question;
        $positive_answer = 0;
        $negative_answer = 0;
        $empty_answer    = 0;

        $root_answer = (str_replace("A", 0, $answer->answer));
        $root_answer = (str_replace("B", 1, $root_answer));
        $root_answer = (str_replace("C", 2, $root_answer));
        $root_answer = json_decode(str_replace("D", 3, $root_answer));

        $questions = ExamQuestion::where('exam_id', $request->exam_id)->limit($total_question)->with('examQuestionOptions')->get();

// return $answer->answer;

// return $questions;
        $modify_question = [];

        foreach ($questions as $key => $item) {

            if ($item->examQuestionOptions->count() > 0) {

                foreach ($item->examQuestionOptions as $ie_key => $ie) {

                    if ($ie->is_answer == 1) {

                        if ($ie_key == $root_answer->$key) {
                            ++$positive_answer;
                            $item['is_correct']   = 1;
                            $item['given_answer'] = $root_answer->$key;
                        } elseif ($root_answer->$key == '') {
                            ++$empty_answer;
                            $item['is_correct']   = 2;
                            $item['given_answer'] = $root_answer->$key;
                        } else {
                            ++$negative_answer;
                            $item['is_correct']   = 3;
                            $item['given_answer'] = $root_answer->$key;
                        }

                        break;

                    }

                }

            } else {
                ++$empty_answer;
                $item['is_correct']   = 2;
                $item['given_answer'] = '';
            }

            $modify_question[] = $item;
        }

        $data['total_correct_answer']   = $positive_answer;
        $data['total_incorrect_answer'] = $negative_answer;
        $data['positive_mark']          = $positive_answer * $answer->exam->per_question_positive_mark;
        $data['negative_mark']          = $negative_answer * $answer->exam->per_question_negative_mark;
        $data['obtained_mark']          = $data['positive_mark'] - $data['negative_mark'];
        $data['empty_mark']             = $empty_answer;
        $data['questions']              = $modify_question;

        return $this->successMessage('ok', $data);
    }

}
