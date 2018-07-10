@extends('admin.layout.master')
@section('js')
    <script type="text/javascript" src='https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js'></script>
    <script type="text/javascript">
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.extraAllowedContent = '*(*)';
        CKEDITOR.replace('description_en');
        CKEDITOR.replace('description_ar');
    </script>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Page Add
            <small>Manage Pages</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/admin/pages') }}">Pages</a></li>
            <li class="active">Add Page</li>
        </ol>
    </section>
    <section class="content" id="app">

    <component is='auth-validate' inline-template> 
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Page</h3>
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/pages') }}" enctype="multipart/form-data" @keydown = "removeclassfunction($event.target.name)">
                        <div class="box-body">
                            {{ csrf_field() }}
                            @include('flash')
                            <div class="form-group{{ $errors->has('title_en') ? ' has-error' : '' }}">
                                <label for="title_en" class="col-md-3 control-label">Page Title(English) <span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="title_en" type="text" class="form-control" name="title_en" value="{{ old('title_en') }}">

                                    @if ($errors->has('title_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('title_ar') ? ' has-error' : '' }}">
                                <label for="title_ar" class="col-md-3 control-label">Page Title(Arabic) <span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="title_ar" type="text" class="form-control" name="title_ar" value="{{ old('title_ar') }}" autofocus>

                                    @if ($errors->has('title_ar'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('title_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description_en') ? ' has-error' : '' }}">
                                <label for="description_en" class="col-md-3 control-label">description (English) <span class="red">*</span></label>

                                <div class="col-md-7">
                                    <textarea rows="5" id="description_en" type="text" class="form-control" name="description_en">{{ old('description_en') }}</textarea>
                                    @if ($errors->has('description_en'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description_en') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                              <div class="form-group {{ $errors->has('description_ar') ? ' has-error' : '' }}">
                                <label for="description_ar" class="col-md-3 control-label">description (Arabic) <span class="red">*</span></label>

                                <div class="col-md-7">
                                    <textarea rows="5" id="description_ar" type="text" class="form-control" name="description_ar">{{ old('description_ar') }}</textarea>
                                    @if ($errors->has('description_ar'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description_ar') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label for="slug" class="col-md-3 control-label">slug <span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') }}" autofocus>

                                    @if ($errors->has('slug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                          
                            <div class="clear-fix"></div>
                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                    <a type="button" href="{{ url('/admin/pages') }}" class="btn btn-danger">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </component>
    </section>
@endsection
