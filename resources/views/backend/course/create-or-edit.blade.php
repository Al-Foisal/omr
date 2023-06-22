@extends('backend.layouts.master')
@section('title', request('id') ? 'Update' : 'Create' . ' Course')
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
                <div class="col-lg-12">
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
                                <div class="form-group">
                                    <label for="name">Name*</label>
                                    <input type="text" class="form-control" id="name" placeholder="Name"
                                        name="name" value="{{ $data->name ?? '' }}">
                                </div>
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">Select Subjects</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- checkbox -->
                                                <div class="form-group">
                                                    @foreach ($subject as $item)
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="{{ $item->id }}" value="{{ $item->id }}"
                                                                name="subject_id[]"
                                                                @if ($data) {{ in_array($item->id, explode(',', $data->subject_id)) ? 'checked' : '' }} @endif>
                                                            <label for="{{ $item->id }}" class="custom-control-label">
                                                                {{ $item->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <div class="form-group">
                                    <label for="name">Details(optional)</label>
                                    <textarea type="text" class="form-control" id="summernote" name="details">
                                        {!! $data->details??'' !!}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="name">Purchase List(optional)</label>
                                    <textarea type="text" class="form-control" id="purchase_link" rows="3" placeholder="Purchase link"
                                        name="purchase_link">{{ $data->purchase_link ?? '' }}</textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
