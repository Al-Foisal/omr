@extends('backend.layouts.master')
@section('title', $user->name . ' details')

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
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">SL.</th>
                                    <th>Exam Details</th>
                                    <th>Course Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exam as $e_item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>Name: </strong> {{ $e_item->name }}, <br>
                                            <strong>Questions:
                                            </strong>{{ $e_item->total_question . ':' . $e_item->per_question_positive_mark . ':-' . $e_item->per_question_negative_mark }}
                                        </td>
                                        <td>
                                            <strong>Course: </strong> {{ $e_item->course->name }}, <br>
                                            <strong>Subject: </strong> {{ $e_item->subject->name }}
                                        </td>
                                        <td>Running</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <h3 class="text-primary"><img src="{{ asset($user->image) }}"
                                style="height:100px;border-radius: 55%;"> {{ $user->name }}</h3>

                        <br>
                        <div class="text-muted">
                            <p class="text-sm">Email
                                <b class="d-block">{{ $user->email }}</b>
                            </p>
                            <p class="text-sm">Phone
                                <b class="d-block">{{ $user->phone }}</b>
                            </p>
                            <p class="text-sm">Registration ID
                                <b class="d-block">{{ $user->registration_id }}</b>
                            </p>
                            <p class="text-sm">Status
                                <b class="d-block">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</b>
                            </p>
                        </div>

                        <h5 class="mt-5 text-muted">Enrolled Courses</h5>
                        <ul class="list-unstyled">
                            @foreach ($user->courses as $course)
                                <li>
                                    <p href=""
                                        class="btn-link @if ($course->status == 0) {{ 'text-danger' }} @else {{ 'text-success' }} @endif">
                                        <i class="far fa-fw fa-file-word"></i>
                                        {{ $course->course->name }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-5 mb-3 d-flex justify-content-start">
                            <form action="{{ route('admin.auth.updateStatus', $user) }}" method="post">
                                @csrf
                                <button type="submit" onclick="return(confirm('Are you sure?'))"
                                    class="btn btn-{{ $user->status == 1 ? 'danger' : 'success' }} btn-xs mr-1">
                                    {{ $user->status == 1 ? 'Inactive' : 'Active' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.auth.studentDelete', $user) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return(confirm('Are you sure?'))"
                                    class="btn btn-danger btn-xs">
                                    {{ 'Delete' }}
                                </button>
                            </form>
                        </div>
                    </div> --}}
                    @include('backend.layouts.partials._user-info')
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
