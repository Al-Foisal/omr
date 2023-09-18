@extends('backend.layouts.master')
@section('title', 'Question ' . $exam->name)
@section('cssLink')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css"> --}}
    <style>
        /* preloader css */
        /* Pre-loader CSS */
        .page-loader {
            width: 100%;
            height: 100vh;
            position: absolute;
            background: #272727;
            z-index: 1000;

            .txt {
                color: #666;
                text-align: center;
                top: 40%;
                right: 9%;
                position: relative;
                text-transform: uppercase;
                letter-spacing: 0.3rem;
                font-weight: bold;
                line-height: 1.5;
            }
        }

        /* Spinner animation */
        .spinner {
            position: relative;
            top: 35%;
            right: 10%;
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
            animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0)
            }

            100% {
                -webkit-transform: scale(1.0);
                opacity: 0;
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            100% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
                opacity: 0;
            }
        }

        /* preloader css */
    </style>
@endsection
@section('backend')
    <div class="page-loader">
        <div class="spinner"></div>
        <div class="txt">Loading...</div>
    </div>
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
                                    <h3 class="card-title mr-5">Course Exam Details</h3>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#previewAnswer"
                                        onclick="previewAnswer(this, '{{ $exam->id }}', '{{ $exam->subject_id }}', '{{ $exam->total_question }}')"
                                        data-url="{{ route('admin.examQuestion.previewAnswer') }}">Preview Answer</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#preview"
                                        onclick="preview(this, '{{ $exam->id }}', '{{ $exam->subject_id }}', '{{ $exam->total_question }}')"
                                        data-url="{{ route('admin.examQuestion.preview') }}">Preview Question</button>

                                    <!-- Button trigger modal -->

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#importQuestion">
                                        Import Question
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="importQuestion" tabindex="-1"
                                        aria-labelledby="importQuestionLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="importQuestionLabel">Upload Excel file</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.ie.import') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="hidden" name="exam_id"
                                                                    value="{{ $exam->id }}">
                                                                <input type="hidden" name="subject_id"
                                                                    value="{{ $exam->subject_id }}">
                                                                <input type="file" class="custom-file-input"
                                                                    id="inputGroupFile02" name="file" required>
                                                                <label class="custom-file-label" for="inputGroupFile02"
                                                                    aria-describedby="inputGroupFileAddon02">Choose
                                                                    file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <button type="submit" class="input-group-text"
                                                                    id="inputGroupFileAddon02">Upload</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#importQuestionAnswer">
                                        Import Question Answer
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="importQuestionAnswer" tabindex="-1"
                                        aria-labelledby="importQuestionAnswerLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="importQuestionAnswerLabel">Upload Excel file
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.ie.importAnswer') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="input-group mb-3">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="inputGroupFile023" name="answerfile" required>
                                                                <label class="custom-file-label" for="inputGroupFile023"
                                                                    aria-describedby="inputGroupFileAddon02">Choose
                                                                    file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                                <button type="submit" class="input-group-text"
                                                                    id="inputGroupFileAddon02">Upload</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.ie.export') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                        <input type="hidden" name="subject_id" value="{{ $exam->subject_id }}">
                                        <input type="hidden" name="total_question" value="{{ $exam->total_question }}">
                                        <button type="submit" class="btn btn-success">Export Model</button>
                                    </form>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <strong>Course Name: </strong> <i>{{ $exam->course->name }}</i> <br>
                                    <strong>Subject Name: </strong> <i>{{ $exam->subject->name }}</i> <br>
                                    <strong>Exam Name: </strong> <i>{{ $exam->name }}</i> <br>
                                    <strong>Exam Code: </strong> <i>{{ $exam->code ?? 'N/A' }}</i> <br>
                                    <strong>Per Question Mark: </strong> <i>{{ $exam->per_question_positive_mark }}</i>
                                    <br>
                                    <strong>Per Question Negative Mark: </strong>
                                    <i>{{ $exam->per_question_negative_mark }}</i> <br>
                                    <strong>Total Question: </strong> <i>{{ $exam->total_question }}</i> <br>
                                    <strong>Exam Status: </strong> <i>{{ $exam->status == 1 ? 'Published' : 'Draft' }}</i>
                                    <br>
                                    <strong>Topic scerial number:</strong> <br>
                                    <div class="pl-3">
                                        <div class="">
                                            @foreach ($subject_topic as $s_topic)
                                                <strong>{{ $s_topic->id . ' - ' . $s_topic->name }}</strong> <br>
                                            @endforeach
                                        </div>
                                    </div>
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
                                    <label for="">Question Name</label>
                                    <textarea class="summernote11" id="question_name_{{ $loop->iteration }}" placeholder="Enter question name here">{!! $eq->question_name !!}</textarea>
                                </div>

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
                                                    <div class="d-flex justify-content-start">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio"
                                                                @if ($i == 0) {{ 'checked' }} @endif
                                                                id="is_answer{{ $loop->iteration }}_{{ $i }}"
                                                                name="is_answer_{{ $loop->iteration }}" value="0"
                                                                @if (
                                                                    $eq->subject_topic_id != null &&
                                                                        $eq->examQuestionOptions->count() > 0 &&
                                                                        $eq->examQuestionOptions[$i]->is_answer == 1) {{ 'checked' }} @endif>
                                                            <label
                                                                for="is_answer{{ $loop->iteration }}_{{ $i }}">
                                                            </label>
                                                        </div>
                                                        <textarea class="summernote{{ $i }}" id="option_{{ $loop->iteration }}_{{ $i }}">{{ $eq->examQuestionOptions[$i]->option ?? null }}</textarea>
                                                    </div>
                                                    <br>
                                                    <br>
                                                @endfor

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Question Explanation</label>
                                    <textarea class="summernote22" id="question_explanation_{{ $loop->iteration }}"
                                        placeholder="Enter question explanation here">{!! $eq->question_explanation !!}</textarea>
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

    <div class="modal fade" id="preview">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Question paper view</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="previewQuestion">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printQuestion()">Print</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="previewAnswer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Question answer view</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="previewQuestionAnswer">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default closeButton"
                        onclick="closeModal('previewAnswer')">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printAnswer()">Print</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('jsSource')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
@endsection

@section('jsScript')
    <script>
        function hideLoader() {
            $('.page-loader').fadeOut('slow');
        }
    </script>
    <script src="{{ asset('summernote-math.js') }}"></script>

    <script>
        $('.summernote11').summernote({
            height: 100,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
        $('.summernote22').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
        $('.summernote0').summernote({
            height: 50,
            width: 1000,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
        $('.summernote1').summernote({
            height: 50,
            width: 1000,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
        $('.summernote2').summernote({
            height: 50,
            width: 1000,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
        $('.summernote3').summernote({
            height: 50,
            width: 1000,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['insert', ['picture', 'link', 'math']],
                ['para', ['paragraph']],
                ['misc', ['codeview']]
            ],
        });
    </script>
    <script>
        // const Toast = Swal.mixin({
        //     toast: true,
        //     position: 'top-end',
        //     showConfirmButton: false,
        //     timer: 3000,
        //     timerProgressBar: true,
        //     didOpen: (toast) => {
        //         toast.addEventListener('mouseenter', Swal.stopTimer)
        //         toast.addEventListener('mouseleave', Swal.resumeTimer)
        //     }
        // });
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
            for (var i = 0; i < 4; i++) {
                console.log(i + $("#option_" + loop_iteration + "_" + i).val().trim());
                if (
                    $("#option_" + loop_iteration + "_" + i).val() == '' ||
                    $("#option_" + loop_iteration + "_" + i).val() == null
                ) {
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

        function preview(e, exam_id, exam_subject_id, total_question) {
            var url = $(e).data('url');

            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    exam_id: exam_id,
                    exam_subject_id: exam_subject_id,
                    total_question: total_question,
                },
                dataType: "html",
                cache: false,
                success: function(response) {

                    $("#previewQuestion").html(response);

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

        function previewAnswer(e, exam_id, exam_subject_id, total_question) {
            var url = $(e).data('url');

            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    exam_id: exam_id,
                    exam_subject_id: exam_subject_id,
                    total_question: total_question,
                },
                dataType: "html",
                cache: false,
                success: function(response) {

                    $("#previewQuestionAnswer").html(response);

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

        function printQuestion() {
            const printContents = document.getElementById('question').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function printAnswer() {
            const printContents = document.getElementById('answer').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function closeModal(modal) {
            $("#previewAnswer").removeClass('show');
            $("#previewAnswer").hide();
            $("#previewAnswer").removeAttr("style");
            $("#previewAnswer").removeAttr("aria-modal");
            $("#previewAnswer").removeAttr("role");
            $("#previewAnswer").modal("hide");

            if ($('body').hasClass('modal-backdrop')) {
                $('body').removeClass('modal-backdrop');
            }
            // if ($('body').hasClass('fade')) {
            //     $('body').removeClass('fade');
            // }
            // if ($('body').hasClass('modal-backdrop')) {
            //     $('body').removeClass('modal-backdrop');
            // }
        }
        // $('.closeButton').on('click', function() {
        //     console.log('ok');
        //     $("#previewAnswer").modal('hide');
        // });
    </script>
@endsection
