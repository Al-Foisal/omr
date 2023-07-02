@extends('backend.layouts.master')
@section('title', request('id') ? 'Update' : 'Create' . ' Course')
@section('cssStyle')
    <style>
        .hide {
            display: none;
        }
    </style>
@endsection
@section('backend')
    <!-- Content Header (Course header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ request('id') ? 'Update' : 'Create' }} Course</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create Course</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form
                            action="{{ request('id') ? route('admin.course.storeOrUpdate', $data->id) : route('admin.course.storeOrUpdate') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (request('id'))
                                @method('put')
                            @endif
                            <div class="card-body">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Course Name<span class="text-danger">*</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="name" placeholder="Name"
                                                name="name" value="{{ $data->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">Details(optional)</label>
                                    <textarea type="text" class="form-control" id="summernote" name="details">
                                        {!! $data->details ?? '' !!}
                                    </textarea>
                                </div>
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Purchase List(optional)<span class="text-danger">*</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="url" class="form-control" id="purchase_link" rows="3"
                                                placeholder="Purchase link" value="{{ $data->purchase_link ?? '' }}"
                                                name="purchase_link">
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Add Subject<span class="text-danger">*</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="input-group hdtuto control-group lst increment">
                                                <input type="text" name="subject[]" class="myfrm form-control"
                                                    placeholder="Enter subject name">
                                                <input type="hidden" name="subject_id[]">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-success" type="button"><i
                                                            class="far fa-plus-square"></i></button>
                                                </div>
                                            </div>

                                            <div class="clone hide">
                                                <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                                    <input type="text" name="subject[]" class="myfrm form-control"
                                                        placeholder="Enter subject name">
                                                    <input type="hidden" name="subject_id[]">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-danger" type="button"> <i
                                                                class="far fa-minus-square"></i></button>

                                                    </div>
                                                </div>
                                            </div>
                                            @if (request('id'))
                                                @foreach ($subject as $s_item)
                                                    <div class="clone">
                                                        <div class="hdtuto control-group lst input-group"
                                                            style="margin-top:10px">
                                                            <input type="text" name="subject[]"
                                                                class="myfrm form-control" placeholder="Enter subject name"
                                                                value="{{ $s_item->name }}">
                                                            <input type="hidden" name="subject_id[]"
                                                                value="{{ $s_item->id }}">
                                                            <div>
                                                                {{-- <a onclick="return confirm('Are you sure want to {{ $s_item->status == 1 ? 'inactive' : 'active' }} this item?')"
                                                                    href="{{ route('admin.course.updateCourseSubjectStatus', $s_item->id) }}"
                                                                    class="btn btn-outline-{{ $s_item->status == 1 ? 'danger' : 'info' }}"
                                                                    type="button">{{ $s_item->status == 1 ? 'Make as Inactive' : 'Make as Active' }}
                                                                </a> --}}
                                                                <div class="mt-1">
                                                                    <input type="checkbox" name="my-checkbox" checked
                                                                        data-bootstrap-switch data-off-color="danger"
                                                                        data-on-color="success">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="add"></div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success rounded"><strong>Next</strong> <i
                                        class="fa fa-long-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-2"></div>

                <!-- /.card -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('name')

@endsection
