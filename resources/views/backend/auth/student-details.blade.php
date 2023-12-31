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
            @php
                if (request()->ct == 'c') {
                    $user_courses = $user->courses->where('status', 1);
                } elseif (request()->ct == 'p') {
                    $user_courses = $user->courses->where('status', 0);
                } else {
                    $user_courses = $user->courses;
                }
            @endphp
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-9 order-2 order-md-1">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">SL.</th>
                                    <th>Course Name</th>
                                    <th>Enrollment Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user_courses as $uc)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $uc->course->name }}</td>
                                        <td>
                                            {{ $uc->created_at->format('d F, Y') }}
                                        </td>
                                        <td class="d-flex justify-content-start">

                                            @if (request()->ct == 'p')
                                                <form action="{{ route('admin.studentPanel.updateStatus', $uc) }}"
                                                    method="post">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return(confirm('Are you sure want to approve this item?'))"
                                                        class="btn btn-success btn-xs mr-2">
                                                        {{ 'Approve' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.studentPanel.delete', $uc->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"
                                                        onclick="return(confirm('Are you sure want to delete this item?'))"
                                                        class="btn btn-danger btn-xs">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <a
                                                    href="{{ route('admin.auth.studentCourseDetails', [request()->id, $uc->course_id]) }}" class="btn btn-primary btn-xs">View Details</a>
                                            @endif
                                        </td>
                                    </tr>
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
