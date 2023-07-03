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

        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Enrolled Courses</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ count($user->courses) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Confirmed Courses</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ count($user->courses->where('status', 1)) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Pending Courses</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ count($user->courses->where('status', 0)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Registered Course Exam List</h4>
                                @foreach ($exam as $e_item)
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg"
                                            alt="user image">
                                            <span class="username">
                                                <a href="javascript:;">{{ $e_item->name }}</a>
                                            </span>
                                            <span class="description">Incomplete - 7:45 PM today</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <p>
                                            Lorem ipsum represents a long-held tradition for designers,
                                            typographers and the like. Some people hate it and argue for
                                            its demise, but others ignore.
                                        </p>

                                        {{-- <p>
                                        <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo
                                            File 1 v2</a>
                                    </p> --}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
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
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
