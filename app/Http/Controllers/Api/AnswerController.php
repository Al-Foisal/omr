<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            $answer                      = new Answer();
            $answer->user_id             = $request->user_id;
            $answer->exam_id             = $request->exam_id;
            $answer->course_id           = $request->course_id;
            $answer->subject_id          = $request->subject_id;
            $answer->answer              = $request->answer;
            $answer->answer_input_image  = $answer_input_image ?? null;
            $answer->answer_output_image = $answer_output_image ?? null;
            $answer->save();

            //claculating exam result
            $total_question  = (int) $answer->exam->total_question;
            $positive_answer = 0;
            $negative_answer = 0;
            $empty_answer    = 0;

            $root_answer = (str_replace("A", 0, $answer->answer));
            $root_answer = (str_replace("B", 1, $root_answer));
            $root_answer = (str_replace("C", 2, $root_answer));
            $root_answer = json_decode(str_replace("D", 3, $root_answer));

            $questions = ExamQuestion::where('exam_id', $request->exam_id)->limit($total_question)->with('examQuestionOptions')->get();

            foreach ($questions as $key => $item) {

                if ($item->examQuestionOptions->count() > 0) {

                    foreach ($item->examQuestionOptions as $ie_key => $ie) {

                        if ($ie->is_answer == 1) {

                            if ($ie_key == $root_answer->$key) {
                                ++$positive_answer;
                            } elseif ($root_answer->$key == '') {
                                ++$empty_answer;
                            } else {
                                ++$negative_answer;
                            }

                            break;

                        }

                    }

                } else {
                    ++$empty_answer;
                }

            }

            $exam_details = Exam::where('id', $answer->exam_id)->first();

            $obtained_mark = $positive_answer * $exam_details->per_question_positive_mark - $negative_answer * $exam_details->per_question_negative_mark;

            $answer->obtained_mark   = $obtained_mark;
            $answer->positive_answer = $positive_answer;
            $answer->negative_answer = $negative_answer;
            $answer->empty_answer    = $empty_answer;
            $answer->save();

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

        $modify_question = [];
        $subject_topic   = [];
        $topic           = [];
        $details         = [
            'name'             => '',
            'total_question'   => 0,
            'attempt_question' => 0,
            'correct_answer'   => 0,
            'incorrect_answer' => 0,
            'obtained_mark'    => 0,
            'percentage'       => 0,

        ];

        foreach ($questions as $key => $item) {

            if (!in_array($item->subject_topic_id, $subject_topic)) {
                $subject_topic[]                           = $item->subject_topic_id;
                $topic['details'][$item->subject_topic_id] = $details;

            }

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

            //updating subject topic
            $topic['details'][$item->subject_topic_id] = [
                'name'             => $item->subjectTopic->name,
                'total_question'   => $topic['details'][$item->subject_topic_id]['total_question'] + 1,
                'attempt_question' => $item->given_answer != '' ? $topic['details'][$item->subject_topic_id]['attempt_question'] + 1 : 0,
                'correct_answer'   => $item->is_correct == 1 ? $topic['details'][$item->subject_topic_id]['correct_answer'] + 1 : $topic['details'][$item->subject_topic_id]['correct_answer'] + 0,
                'incorrect_answer' => $item->is_correct == 3 ? $topic['details'][$item->subject_topic_id]['incorrect_answer'] + 1 : $topic['details'][$item->subject_topic_id]['incorrect_answer'] + 0,
                'obtained_mark'    => ($topic['details'][$item->subject_topic_id]['correct_answer'] * $item->exam->per_question_positive_mark) - ($topic['details'][$item->subject_topic_id]['incorrect_answer'] * $item->exam->per_question_negative_mark),
                'percentage'       => '',
            ];

        }

        foreach ($topic['details'] as $key => $m_details) {
            $topic['details'][$key]['percentage'] = ($m_details['correct_answer'] / $m_details['total_question']) * 100;
        }

        $data['total_correct_answer']   = $positive_answer;
        $data['total_incorrect_answer'] = $negative_answer;
        $data['positive_mark']          = $positive_answer * $answer->exam->per_question_positive_mark;
        $data['negative_mark']          = $negative_answer * $answer->exam->per_question_negative_mark;
        $data['obtained_mark']          = $data['positive_mark'] - $data['negative_mark'];
        $data['empty_mark']             = $empty_answer;
        $data['questions']              = $modify_question;
        $data['topic']                  = $topic;

        $get_exam_answer = Answer::where('exam_id', $answer->exam_id)->orderBy('obtained_mark', 'desc')->pluck('user_id')->toArray();

        $data['my_position']      = array_search(Auth::id(), $get_exam_answer) + 1;
        $data['total_given_exam'] = count($get_exam_answer);

        return $this->successMessage('ok', $data);
    }

}
