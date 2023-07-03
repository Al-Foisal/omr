@extends('backend.layouts.master')
@section('title', 'dashboard')

@section('backend')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_courses }}</span>
                            <span class="info-box-number">Number Of Courses</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_students }}</span>
                            <span class="info-box-number">Number Of Students</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-danger">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_registration }}</span>
                            <span class="info-box-number">Number of Pending Students</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_month_enroll }}</span>
                            <span class="info-box-number">This Month Enrollments</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_year_enroll }}</span>
                            <span class="info-box-number">This Year Enrollments</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box shadow-lg">
                        <span class="info-box-icon bg-danger">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $total_enroll }}</span>
                            <span class="info-box-number">Total Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
