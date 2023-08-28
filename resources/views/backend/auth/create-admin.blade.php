@extends('backend.layouts.master')
@section('title', '')
@section('cssLink')
@endsection
@section('cssStyle')
@endsection

@section('backend')
    <div class="">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ route('admin.dashboard') }}" class="h1">
                    Create New Admin
                </a>
            </div>
            <div class="card-body mb-4" style="width: 80%;margin:auto;">

                <form action="{{ route('admin.auth.storeAdmin') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="email">Full name*</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name" name="name">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Email</label>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Phone</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Phone Number" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Image</label>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>
                    </div>

                    <label for="">Address</label>
                    <div class="input-group mb-3">
                        <textarea type="text" class="form-control" placeholder="Address" name="address"></textarea>
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
                                            <input type="checkbox" id="admin" name="users" value="1" >
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
                                            <input type="checkbox" id="course" name="course" value="1">
                                            <label for="course">
                                                Course
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="exam" name="exam" value="1">
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
                                            <input type="checkbox" id="general" name="general" value="1">
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
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
@endsection

@section('jsSource')
@endsection
@section('jsScript')
@endsection
