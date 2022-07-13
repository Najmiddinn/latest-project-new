@extends('layouts.mainBackend')


@section('title')
    {{__('msg.Update')}}
@endsection

@section('content')
    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> {{__('msg.Update')}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}"> {{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('book-category.index')}}">kitob kategoriyasi</a></li>
                            <li class="breadcrumb-item active"> {{__('msg.Update')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('book-category.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name_id">{{__('msg.Name')}}</label>
                            <input type="text" name="name" id="name_id" class="form-control @error('name') error-data-input is-invalid @enderror" value="{{ $model->name, old('name') }}" required>
                            <span class="error-data">@error('name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="order_by_id">Tartibi</label>
                            <input type="text" name="order_by" id="order_by_id" class="form-control @error('order_by') error-data-input is-invalid @enderror" value="{{ $model->order_by,  old('order_by') }}">
                            <span class="error-data">@error('order_by'){{$message}}@enderror</span>
                        </div>


                        <div class="form-group">
                            <label for="parent_id">{{__('msg.Parent')}}</label>
                            <select name="parent" id="parent_id" class="form-control" value="{{ old('parent') }}">
                                @if(!empty($category))

                                    <option value="{{$model->getParentName['id']}}" selected>{{$model->getParentName['name']}}</option>
                                  
                                    @foreach($category as $ct)
                                        <option value="{{$ct->id}}">{{$ct->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('parent'){{$message}}@enderror</span>

                        </div>

                        <div class="form-group">
                            <label for="status">{{__('msg.Status')}}</label>
                            <select name="status" id="status" class="form-control @error('status') error-data-input is-invalid @enderror" >
                            @if ($model->status==1)
                                <option value="1" selected>{{__('msg.Active')}}</option>
                            @else
                                <option value="0" selected>{{__('msg.No Active')}}</option>
                            @endif
                                <option value="1" >{{__('msg.Active')}}</option>
                                <option value="0" >{{__('msg.No Active')}}</option>
                            
                            </select>
                            <span class="error-data">@error('status'){{$message}}@enderror</span>
                        </div>

                    <div class="card-footer">
                        <a href="{{route('course.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                        <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
        //   $('#course_yearId').inputmask('format':'dd/mm/yyyy');
          $("#course_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

        });
    </script>
@endsection

