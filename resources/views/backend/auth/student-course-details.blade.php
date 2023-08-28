@extends('backend.layouts.master')
@section('title', $user->name . ' details')

@section('cssStyle')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
@endsection
@section('backend')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $user->name . ' details' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Student</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        @include('backend.layouts.partials._user-navbar')
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-9 order-2 order-md-1">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">SL.</th>
                                    <th>Exam Details</th>
                                    <th>Course Details</th>
                                    <th>Assesment</th>
                                    <th>Merit Position</th>
                                    <th>OMR Sheet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exam as $e_item)
                                    @if (isset($e_item->answer))
                                        @php
                                            $get_exam_answer = App\Models\Answer::where('exam_id', $e_item->id)
                                                ->orderBy('obtained_mark', 'desc')
                                                ->pluck('user_id')
                                                ->toArray();
                                            
                                            $my_position = array_search(Auth::id(), $get_exam_answer) + 1;
                                            $total_given_exam = count($get_exam_answer);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>Name: </strong> {{ $e_item->name }}, <br>
                                                <strong>Questions:
                                                </strong>{{ $e_item->total_question . ':' . $e_item->per_question_positive_mark . ':-' . $e_item->per_question_negative_mark }},
                                                <br>
                                                <strong>Date:</strong>
                                                {{ $e_item->answer->created_at->format('d F, Y') ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <strong>Course: </strong> {{ $e_item->course->name }}, <br>
                                                <strong>Subject: </strong> {{ $e_item->subject->name }}
                                            </td>
                                            <td>
                                                <strong>Obtained Mark: </strong>
                                                {{ $e_item->answer->obtained_mark ?? 'N/A' }},
                                                <br>
                                                <strong>Positive Answer: </strong>
                                                {{ $e_item->answer->positive_answer ?? 'N/A' }}, <br>
                                                <strong>Negative Answer: </strong>
                                                {{ $e_item->answer->negative_answer ?? 'N/A' }}, <br>
                                                <strong>Empty Answer: </strong>
                                                {{ $e_item->answer->empty_answer ?? 'N/A' }},
                                                <br>
                                            </td>
                                            <td>
                                                @if (isset($e_item->answer->obtained_mark))
                                                    {{ $my_position . '/' . $total_given_exam }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ asset($e_item->answer->answer_input_image) }}"
                                                    class="btn btn-primary btn-sm btn-block" download="">Input Image</a>
                                                <a href="{{ asset($e_item->answer->answer_output_image) }}"
                                                    class="btn btn-success btn-sm btn-block" download="">Output Image</a>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><h2>Empty examination</h2></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @include('backend.layouts.partials._user-info')
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
