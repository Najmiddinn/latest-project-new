@extends('layouts.mainBackend')
@section('title') Izlash @endsection


@section('content')

    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Izlash</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item active">Izlash</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>


        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif
            
        <form id="student_form" action="{{route('searchbar.search')}}" method="GET" enctype="multipart/form-data">
            {{-- @csrf --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
    
                            <div class="form-group">
                                <label for="search_key_id">kalit so'z</label>
                                <input type="text" name="search_key" id="search_key_id" class="form-control @error('search_key') error-data-input is-invalid @enderror" value="{{ old('search_key') }}" required>
                                <span class="error-data">@error('search_key'){{$message}}@enderror</span>
                            </div>
                            
    
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">izlash</button>
                            </div>
    
                        </div>
                    </div>
                </div>
             
            </div>
        </form>
        
        {{$noresult}}
      
        @if(!empty($booksmodel))
        <div class="card-body">
            <p>Kiritilgan kalit so'z bo'yicha <b>{{count($booksmodel)}}</b> ta kitob topildi</p>
            <table id="dashboard_datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('msg.Name')}}</th>
                    <th>Muallifi</th>
                    <th>Nashriyot</th>
                    <th>Soni</th>
                    <th>kod</th>
                    @role('super admin')
                    <th>{{__('msg.Actions')}}</th>
                    @endrole
                </tr>
                </thead>
                <tbody>

                @foreach($booksmodel as $key => $book)
                    <tr>
                        <th scope="row">{{ $booksmodel->firstItem()+$key }}</th>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->publisher }}</td>
                        <td>{{ $book->book_count }}</td>
                        <td>{{ $book->book_code }}</td>
                        @role('super admin')
                        <td>
                            <div style="text-align: center;">
                                <a href="{{route('book.edit',$book->id)}}" class="btn btn-primary" title="update">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form style="display: inline-block;" action="{{route('book.destroy',$book->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-data-item btn btn-danger" title="delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endrole
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $booksmodel->links() }}
        </div>
   
    @endif

        @if(!empty($studentsmodel))
        <div class="card-body">
            <p>Kiritilgan kalit so'z bo'yicha <b>{{count($studentsmodel)}}</b> foydalanuvchi topildi</p>
            <table id="dashboard_datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Familyasi</th>
                    <th>Ismi</th>
                    <th>Tug'ilgan sanasi</th>
                    <th>kategoriyasi</th>
                    @role('super admin')
                    <th>{{__('msg.Actions')}}</th>
                    @endrole
                </tr>
                </thead>
                <tbody>

                @foreach($studentsmodel as $key => $student)
                    <tr>
                        <th scope="row">{{ $studentsmodel->firstItem()+$key }}</th>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->birth_date }}</td>
                        <td>{{ $student->getCourse->course_name }}</td>
                       
                        <td>
                            <div style="text-align: center;">
                                <a href="{{route('student.show',$student->id)}}" class="btn btn-primary" title="show">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @role('super admin')
                                    <a href="{{route('student.edit',$student->id)}}" class="btn btn-primary" title="update">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form style="display: inline-block;" action="{{route('student.destroy',$student->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-data-item btn btn-danger" title="delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endrole
                            </div>
                        </td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $studentsmodel->links() }}
        </div>
    
    
    @endif

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $("#dashboard_datatable").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "paging": false
            
        });

        

    });
</script>
@endsection







