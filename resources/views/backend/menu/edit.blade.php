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
                        <h1 class="m-0">{{__('msg.Update')}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}"> {{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('menu.index')}}">Menu</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Update')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('menu.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name_id">{{__('msg.Name')}}</label>
                            <input type="text" name="name" id="name_id" class="form-control @error('name') error-data-input is-invalid @enderror" value="{{ $model->name,old('name') }}" required>
                            <span class="error-data">@error('name'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="url_id">Url</label>
                            <input type="text" name="url" id="url_id" class="form-control @error('url') error-data-input @enderror" value="{{  $model->url,old('url') }}">
                            <span class="error-data">@error('url'){{$message}}@enderror</span>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('course.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                            <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="type_id">{{__('msg.Type')}}</label>
                            <select name="type" id="type_id" class="form-control @error('type') error-data-input @enderror" >
                            
                                <option value="0"  selected>Header</option>
                                <option value="1">Footer</option>
                            
                            </select>
                            <span class="error-data">@error('type'){{$message}}@enderror</span>
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

                        <div class="form-group">
                            <label for="parent_id">{{__('msg.Parent')}}</label>
                            <select name="parent" id="parent_id" class="form-control" value="{{ old('parent') }}">
                                @if(!empty($models))

                                    <option value="{{$model->parent}}">{{$model->getParentName['name']}}</option>
                                   
                                    @foreach($models as $nm)
                                        <option value="{{$nm->id}}">{{$nm->name}}</option>

                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('parent'){{$message}}@enderror</span>

                        </div>
                     
                        <div class="form-group">
                            <label for="order_by_id" >{{__('msg.Order By')}}</label>
                            <input type="number" name="order_by" id="order_by_id" class="form-control" value="{{ $model->order_by, old('order_by') }}">
                            <span class="error-data">@error('order_by'){{$message}}@enderror</span>
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

