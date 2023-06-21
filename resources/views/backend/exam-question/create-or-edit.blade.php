@extends('backend.layouts.master')
@section('title', 'Question ' . $exam->name)
@section('backend')
    <!-- Content Header (Course Exam header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Question Exam: "{{ $exam->name }}"</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Question</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card bg-gradient-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Course Exam Details</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <strong>Course Name: </strong> <i>{{ $exam->course->name }}</i> <br>
                                    <strong>Subject Name: </strong> <i>{{ $exam->subject->name }}</i> <br>
                                    <strong>Exam Name: </strong> <i>{{ $exam->name }}</i> <br>
                                    <strong>Per Question Mark: </strong> <i>{{ $exam->per_question_positive_mark }}</i> <br>
                                    <strong>Per Question Negative Mark: </strong>
                                    <i>{{ $exam->per_question_negative_mark }}</i> <br>
                                    <strong>Total Question: </strong> <i>{{ $exam->total_question }}</i> <br>
                                    <strong>Exam Status: </strong> <i>{{ $exam->status == 1 ? 'Published' : 'Draft' }}</i>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.card -->
            </div>

            {{-- question area starts --}}
            @foreach ($exam_question as $eq)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card @if ($eq->subject_topic_id == null) {{ 'card-secondary' }} @elseif($eq->subject_topic_id && $eq->status == 1) {{ 'card-success' }} @else {{ 'card-warning' }} @endif collapsed-card"
                            id="change_class_status_{{ $eq->id }}">
                            <div class="card-header" class="btn btn-tool" data-card-widget="collapse"
                                style="cursor: pointer;">
                                <h3 class="card-title">Question #{{ $loop->iteration }}</h3>
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Question Name
                                            </h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <textarea class="summernote" id="question_name_{{ $loop->iteration }}" placeholder="Enter question name here">{!! $eq->question_name !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="subject_topic_{{ $loop->iteration }}">Select Subject Topic</label>
                                        <select class="custom-select form-control-border"
                                            id="subject_topic_{{ $loop->iteration }}">
                                            @foreach ($subject_topic as $topic)
                                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3 class="card-title">
                                                        Options with Answer
                                                    </h3>
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="custom-select form-control-border"
                                                        id="subject_topic_{{ $loop->iteration }}">
                                                        <option value="">Select subject topic</option>
                                                        @foreach ($subject_topic as $topic)
                                                            <option value="{{ $topic->id }}"
                                                                @if ($eq->subject_topic_id == $topic->id) {{ 'selected' }} @endif>
                                                                {{ $topic->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="form-group clearfix">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <div class="icheck-success d-inline">
                                                        <input type="radio"
                                                            id="is_answer{{ $loop->iteration }}_{{ $i }}"
                                                            name="is_answer_{{ $loop->iteration }}" value="0"
                                                            @if ($eq->subject_topic_id != null && $eq->examQuestionOptions[$i]->is_answer == 1) {{ 'checked' }} @endif>
                                                        <label for="is_answer{{ $loop->iteration }}_{{ $i }}">
                                                            <input type="text" class="form-control"
                                                                id="option_{{ $loop->iteration }}_{{ $i }}"
                                                                value="{{ $eq->examQuestionOptions[$i]->option ?? '' }}">
                                                        </label>
                                                    </div>
                                                    <br>
                                                    <br>
                                                @endfor

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Question Explanation
                                            </h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <textarea class="summernote" id="question_explanation_{{ $loop->iteration }}"
                                                placeholder="Enter question explanation here">{!! $eq->question_explanation !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-success"
                                    onclick="saveQuestion(this, '{{ $loop->iteration }}','{{ $eq->id }}')"
                                    data-url="{{ route('admin.examQuestion.storeOrUpdate') }}">Question Save</button>
                                <button type="button" class="btn btn-outline-warning"
                                    onclick="makeForReview(this, '{{ $eq->id }}')"
                                    data-url="{{ route('admin.examQuestion.makeForReview') }}">Make For Review</button>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            @endforeach
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('jsSource')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('jsScript')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        $(function() {
            $('.summernote').summernote({
                inheritPlaceholder: true,
                placeholder: 'Enter your Inquiry here...',
            });
            $('.summernote').summernote('code', ''); //This line remove summercontent when load
        });

        function saveQuestion(e, loop_iteration, exam_question_id) {


            var url = $(e).data('url');
            var question_name = $("#question_name_" + loop_iteration).val();
            var subject_topic_id = $("#subject_topic_" + loop_iteration).val();
            var options = [];
            var is_answer = [];
            for (var i = 0; i < 4; i++) {
                if ($("#option_" + loop_iteration + "_" + i).val() == null) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Option ' + i + ' is required.'
                    });
                    return;
                    options = [];
                    is_answer = [];
                }
                var is_answer_checked = document.getElementById("is_answer" + loop_iteration + "_" + i);
                var answer = 0;
                if (is_answer_checked.checked) {
                    answer = 1;
                }
                is_answer.push(answer);
                options.push($("#option_" + loop_iteration + "_" + i).val());
            }
            var question_explanation = $("#question_explanation_" + loop_iteration).val();

            if (!question_name) {
                Toast.fire({
                    icon: 'error',
                    title: 'Question name is required.'
                });
                return;
            }
            if (!subject_topic_id) {
                Toast.fire({
                    icon: 'error',
                    title: 'Subject topic is required.'
                });
                return;
            }

            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    exam_question_id: exam_question_id,
                    question_name: question_name,
                    subject_topic_id: subject_topic_id,
                    options: options,
                    is_answer: is_answer,
                    question_explanation: question_explanation,
                },
                cache: false,
                success: function(response) {

                    if (response.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Question saved successfully'
                        });
                        $("#change_class_status_" + exam_question_id).removeClass('card-warning');
                        $("#change_class_status_" + exam_question_id).addClass('card-success');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong! Please try again.'
                        })
                    }

                },
                async: false,
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong! Please try again.'
                    })
                }
            });


        }

        function makeForReview(e, exam_question_id) {
            var url = $(e).data('url');
            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    exam_question_id: exam_question_id,
                },
                cache: false,
                success: function(response) {

                    if (response.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Question added to draft for review'
                        });
                        $("#change_class_status_" + exam_question_id).addClass('card-warning');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong! Please try again.'
                        })
                    }

                },
                async: false,
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong! Please try again.'
                    })
                }
            });
        }
    </script>
@endsection
