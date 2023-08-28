@extends('backend.layouts.master')
@section('title', 'Update Admin')

@section('backend')
    <div class="">
        <div class="register-logo">
            <a href="{{ route('admin.dashboard') }}" class="h1">
                Pos Inventory
            </a>
        </div>

        <div class="card">
            <div class="card-body register-card-body mb-4">
                <p class="login-box-msg">Edit supportive member</p>

                <form action="{{ route('admin.auth.updateAdmin', $admin) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{ $admin->name }}" name="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{ $admin->phone }}" name="phone">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" value="{{ $admin->email }}" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="image">
                            </div>
                            @if ($admin->image)
                                <img src="{{ asset($admin->image) }}" height="100" width="100" alt="User logo">
                            @endif
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <textarea type="text" rows="2" class="form-control" name="address">{{ $admin->address }}</textarea>
                    </div>
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Respective Admin Role / Access</h3>
                        </div>
                        <div class="card-body">
                            <!-- Minimal style -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="admin" name="users" value="1"
                                                {{ $admin->users ? 'checked' : '' }}>
                                            <label for="admin">
                                                Users
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="course" name="course" value="1"
                                            {{ $admin->course ? 'checked' : '' }}>
                                            <label for="course">
                                                Course
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="exam" name="exam" value="1"
                                            {{ $admin->exam ? 'checked' : '' }}>
                                            <label for="exam">
                                                Examination
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="general" name="general" value="1"
                                            {{ $admin->general ? 'checked' : '' }}>
                                            <label for="general">
                                                General
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-10">
                        </div>
                        <!-- /.col -->
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary btn-block">Update</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <br>
    <br>
@endsection
