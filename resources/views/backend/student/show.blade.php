@extends('layouts.mainBackend')
@section('title') Ko'rish @endsection

@section('content')

<div class="page-header card">
</div>
<div class="card">
<div class="content-header">
  <div class="container-fluid card-block">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Talaba</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__("msg.Home")}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('student.index')}}">Talaba</a></li>
          <li class="breadcrumb-item active">{{__('msg.Show')}}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

</div>

<div class="card">
<div class="card-block table-border-style">
  <table class="table table-borderet table-hover">
      <thead >
          <tr>
              <th>#</th>
              <th>#</th>
          </tr>
      </thead>
      <tbody>
          <tr>
            <td>Familyasi</td>
            <td>{{$model->first_name}}</td>      
          </tr>
          <tr>  
            <td>Ismi</td>
            <td>{{$model->last_name}}</td>
          </tr>
          <tr>  
            <td>Tug'ilgan yili</td>
            <td>{{$model->birth_date}}</td>
          </tr>
          <tr>  
            <td>Kursi</td>
            <td>{{$model->getCourse['course_name']}}</td>
          </tr>
          
      </tbody>
  </table>
  @if (!empty($studentBorowwing))
      
 
  <div class="container">
      
      <p style="font-weight: bold">Jami olgan va qaytargan kitoblari soni : {{ count($studentBorowwing) }} ta</p>
    
  </div>
  <table class="table table-borderet table-hover" id="dashboard_datatable">
      <thead>
        <tr>
          <th>Kitobning nomi</th>
          <th>Kitobning kodi</th>
          <th>Status</th>
          <th>olgan vaqti</th>
          <th>qaytargan vaqti</th>
          <th>{{__('msg.Actions')}}</th>
        </tr>
      </thead>
      <tbody>
       
        @foreach($studentBorowwing as $model)
          <tr>  
            <td>{{ $model->getBook['title'] }}</td>
            <td>{{ $model->book_code }}</td>
            @if($model->status==1)
            <td> <span class="badge badge-success">{{ 'Topshirgan' }}</span></td>
            @else
            <td > <span class="badge badge-danger">{{ 'Topshirmagan' }}</span></td>
            @endif
            <td>{{ $model->date_borrowwed}}</td>
            <td>{{ $model->date_return}}</td>
            @role('super admin')
              <td>
                <div class="index-borowwing" style="text-align: center;">
                  
                    <button type="button" class="data-borowwing-edit btn btn-primary" data-id="{{$model->id}}" data-toggle="modal" data-target="#data-edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    
                  <form style="display: inline-block;" action="{{route('student.borowwing-destroy',$model->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete-data-item" class="delete-data-item btn btn-danger" title="delete">
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
    {{$studentBorowwing->links()}}
    @endif 
    <div class="action-content-view container" style="padding-bottom: 25px">
                
      <a href="{{route('student.index')}}" class="btn btn-primary" title="cancel">
          cancel
      </a>
      @role('super admin')
      <a href="{{route('student.edit',$model->id)}}" class="btn btn-success" title="update">
          update
      </a>  
      <form style="display: inline-block;" action="{{route('student.destroy',$model->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" id="delete-data-item" class="delete-data-item btn btn-danger" title="delete">
          <i class="ti-trash"></i> delete
        </button>
      </form>
      
      
      @endrole
      
    </div>
  </div>
</div>



<div class="modal fade" id="data-edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="borowwing_edit_form" action="" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Berilgan kitobni tahrirlash</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="text-center">
        <div id="for-preloader"></div>
      </div>
        <div class="modal-body">
          <div class="row">
              <div class="col-md-12">
                  <div class="" id="borowwingt_error" role="alert"></div>
                  <div class="card-body">
                      <div id="data-borowwing">
                          
                          <div class="form-group" id="student_id_div">
                              <label for="student_id">Talaba</label>
                              <select required name="student_edit" id="student_id_edit"  data-placeholder="Talabani tanlang" class="form-control select2 @error('student') is-invalid error-data-input @enderror" value="{{ old('student') }}">
                                  
                              </select>
                              <span class="error-data">@error('student'){{$message}}@enderror</span>
                          </div>

                          <div class="form-group" id="title_id_div">
                              <label for="title_id_edit">Kitob</label>
                              <select required name="title_edit" id="title_id_edit"  data-placeholder="Kitobni tanlang" class="form-control select2 @error('title_edit') is-invalid error-data-input @enderror" value="{{ old('title_edit') }}">
                                 
                              </select>
                              <span class="error-data">@error('title_edit'){{$message}}@enderror</span>
                          </div>

                          <div class="form-group">
                              <label for="book_code_edit_id">Kitob kodi</label>
                              <input type='text' id="book_code_edit_id" required maxlength="20" name="book_code" class="form-control @error('book_code') is-invalid error-data-input @enderror" value="{{ old('book_code') }}" >
                              <span class="error-data">@error('book_code'){{$message}}@enderror</span>
                          </div>

                          <div class="form-group">
                              <label for="date_borrowwedId_edit">Kitob berilgan sana</label>
                              <input type='text' id="date_borrowwedId_edit" required name="date_borrowwed_edit" class="form-control @error('date_borrowwed') is-invalid error-data-input @enderror" value="{{ old('date_borrowwed') }}" >
                              <span class="error-data">@error('date_borrowwed'){{$message}}@enderror</span>
                          </div>
                          <div class="form-group">
                              <label for="date_returnId_edit">Kitob qaytarilgan sana</label>
                              <input type='text' id="date_returnId_edit" name="date_return_edit" class="form-control @error('date_return') is-invalid error-data-input @enderror" value="{{ old('date_return') }}" >
                              <span class="error-data">@error('date_return'){{$message}}@enderror</span>
                          </div>
                          <div class="form-group" id="student_id_div">
                              <label for="status">Status</label>
                              <select required name="status" id="status">

                              </select>
                              <span class="error-data">@error('status'){{$message}}@enderror</span>
                          </div>

                          <input type="hidden" value="" id="borowwing_id">
          
                      </div>
                  </div>
              </div>
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Bekor qilish</button>
        <button type="submit" class="btn btn-success" id="data-borowwing-update">{{__('msg.Create')}}</button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
  <script>
     $('form #date_borrowwedId').datetimepicker({
            format: 'Y-M-D'
        });
        $('form #date_borrowwedId_edit').datetimepicker({
            format: 'Y-M-D'
        });
        $('form #date_returnId_edit').datetimepicker({
            format: 'Y-M-D'
        });

     $('.index-borowwing .data-borowwing-edit').on('click', function (e){
            e.preventDefault();
            let id = $(this).data('id');
            // console.log(id);
            
            $('#borowwing_edit_form').attr('action','/admin/student/student-borowwing-update/'+id);
            $.ajaxSetup({
                beforeSend: function() {
                    // TODO: show your spinner
                    $("#for-preloader").addClass('spinner-border');
                    // $("#for-preloader").css({"witdh":"70px","height":"70px"});
                    $('.modal-body').hide();
                },
                complete: function() {
                    // TODO: hide your spinner
                    $('.modal-body').show();
                    $("#for-preloader").removeClass('spinner-border');
                    // $("#for-preloader").css({"witdh":"0","height":"0",});
                }
            });
            $.get('/admin/borowwing/edit/'+id, function (data) {
                // console.log(data['students']);
                let borrowwed = data['borowwing'];

                $('#student_id_edit').append('<option selected value=' + data.student_id + '>' + data.student_first_name + ' ' + data.student_last_name + ' </option>');
                
                let studentsdata = data['students'];
                for (let i in studentsdata) {
                    $('#student_id_edit').append('<option value=' + studentsdata[i].id + '>' + studentsdata[i].first_name + ' ' + studentsdata[i].last_name +'</option>');
                }

                $('#title_id_edit').append('<option selected value=' + data.bookitem['id'] + '>' + data.bookitem['title'] + ' </option>');
                
                let booksdataedit = data['booksdataedit'];
                for (let i in booksdataedit) {
                    $('#title_id_edit').append('<option value=' + booksdataedit[i].id + '>' + booksdataedit[i].title + '(' + booksdataedit[i].book_count + ')' + '</option>');
                }

                // $('#book_id_edit').val(data.book_code);
                $('#date_borrowwedId_edit').val(borrowwed.date_borrowwed);
                $('#date_returnId_edit').val(borrowwed.date_return);
                $('#book_code_edit_id').val(borrowwed.book_code);
                
                $("#status option").remove();
                if(borrowwed.status==0){
                    $('#status').append('<option value=' + 0 + ' selected> Topshirilmagan </option>');
                    $('#status').append('<option value=' + 1 + '> Topshirilgan </option>');
                }
                if (borrowwed.status==1) {
                    $('#status').append('<option value=' + 1 + ' selected> Topshirilgan </option>');
                    $('#status').append('<option value=' + 0 + '> Topshirilmagan </option>');
                }
                
                $('#borowwing_id').val(borrowwed.id);
                // console.log(data['borowwing']);
            })
        });
        
  </script> 
@endsection







