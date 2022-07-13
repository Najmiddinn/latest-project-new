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
                        <li class="breadcrumb-item"><a href="{{route('elektron-book.index')}}">Elektron kitoblar</a></li>
                        <li class="breadcrumb-item active"> {{__('msg.Update')}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>

    <form action="{{route('elektron-book.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        
                        <div class="form-group">
                            <label for="title">{{__('msg.Name')}}</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') error-data-input is-invalid @enderror" value="{{$model->title, old('title') }}" required>
                            <span class="error-data">@error('title'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="description">Tavsif</label>
                            <input type="text" name="description" id="description" class="form-control @error('description') error-data-input is-invalid @enderror" value="{{$model->description, old('description') }}">
                            <span class="error-data">@error('description'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="category_id">{{__('msg.Category')}}</label>
                              <select style="width: 100%;" name="category" id="category_id" required class="form-control select2 @error('category') is-invalid error-data-input @enderror" >
                                @if(!empty($category))
                                <option value="">--------------------</option>
                                    @foreach($category as $ct)
                                        @if($model->category == $ct->id)
                                            <option value="{{$ct->id}}" selected > {{$ct->name}} </option>
                                        @endif
                                        <option value="{{$ct->id}}">{{$ct->name}}</option>
                                    @endforeach
                                @endif
                              </select>

                            <span class="error-data">@error('category'){{$message}}@enderror</span>
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
                            <label for="book">Fayl</label>
                            <input type="file" name="book" id="book" class="form-control @error('book') error-data-input is-invalid @enderror">
                            <span class="error-data">@error('book'){{$message}}@enderror</span>
                        </div>


                    <div class="card-footer">
                        <a href="{{route('elektron-book.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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
        //   $("#course_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

        });
    </script>
@endsection

