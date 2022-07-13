@extends('layouts.mainBackend')
@section('title') Kitob berish @endsection
@section('content')
    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Kitob berish</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item active">Kitob berish</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="breadcrumb-and-filter">
                <div class="row">
                    <div class="col-md-9">
                        <div class="action-content">
                            <button style="margin-bottom: 10px" class="btn btn-danger delete_all" data-url="{{ route('borowwing.destroyMultiple') }}">Belgilangan qatorlarni o'chirish</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="create-data" style="float: right;">
                        <button id="create_borowwing" type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                            Yaratish
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="" id="borowwing_success" role="alert"></div>
        <div class="" id="borowwing_error" role="alert"></div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
       
        @error('student')<div class="alert alert-danger">{{$message}}</div>@enderror
        @error('book')<div class="alert alert-danger">{{$message}}</div>@enderror
        @error('date_borrowwed')<div class="alert alert-danger">{{$message}}</div>@enderror
        
        <div class="card-body">
            
            <table id="dashboard_datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><input type="checkbox" id="master"></th>
                    {{-- <th>#</th> --}}
                    <th>Familya ism</th>
                    <th>kategoriyasi</th>
                    <th>Kitob</th>
                    <th>Kitob kodi</th>
                    <th>Status</th>
                    <th>Olingan sana</th>
                    <th>Qaytarilgan sana</th>
                    <th>{{__('msg.Actions')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($models as $key => $model)
                    <tr>
                        
                        <td><input type="checkbox" class="sub_chk" data-id="{{$model->id}}"></td>
                        <td><a href="{{route('student.show', $model->student_id)}}">{{ $model->getStudent['first_name'] }} {{ $model->getStudent['last_name'] }}</a></td>
                        <td>{{ $model->getStudentCategory($model->getStudent['course_id']) }}</td>
                        <td>{{ $model->getBook['title'] }}</td>
                        <td>{{ $model->book_code }}</td>
                        @if($model->status==1)
                        <td> <span class="badge badge-success">{{ 'Topshirgan' }}</span></td>
                        @else
                        <td > <span class="badge badge-danger">{{ 'Topshirmagan' }}</span></td>
                        @endif
                        <td>{{ $model->date_borrowwed }}</td>
                        @if($model->date_return)
                        <td> {{ $model->date_return }}</td>
                        @else
                        <td > {{ '-' }}</td>
                        @endif
                        <td>
                            <div class="index-borowwing" style="text-align: center;">
                              
                                <button type="button" class="data-borowwing-edit btn btn-primary" data-id="{{$model->id}}" data-toggle="modal" data-target="#data-edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <form style="display: inline-block;" action="{{route('borowwing.destroy',$model->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-data-item btn btn-danger" title="delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="student_form" action="{{route('borowwing.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Kitob berish</h5>
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
                            <div class="form-group" id="student_id_div">
                                <label for="student_id">Talaba</label>
                                <select required name="student" id="students_create"  data-placeholder="Talabani tanlang" class="form-control select2 @error('student') is-invalid error-data-input @enderror" value="{{ old('student') }}">
                                    <option value="">------------</option>
                                </select>
                                <span class="error-data">@error('student'){{$message}}@enderror</span>
                            </div>
                            
                            <div class="form-group" id="title_id_div">
                                <label for="title_id">Kitob</label>
                                <select required name="title" id="title_id"  data-placeholder="Kitobni tanlang" class="form-control select2 @error('title') is-invalid error-data-input @enderror" value="{{ old('title') }}">
                                    <option value="">------------</option>
                                </select>
                                <span class="error-data">@error('title'){{$message}}@enderror</span>
                            </div>
                            
                            <div class="form-group">
                                <label for="book_code_id">Kitob kodi</label>
                                <input type='text' id="book_code_id" required maxlength="20" name="book_code" class="form-control @error('book_code') is-invalid error-data-input @enderror" value="{{ old('book_code') }}" >
                                <span class="error-data">@error('book_code'){{$message}}@enderror</span>
                            </div>
                            <div class="form-group">
                                <label for="date_borrowwedId">Sana</label>
                                <input type='text' id="date_borrowwedId" required name="date_borrowwed" class="form-control @error('date_borrowwed') is-invalid error-data-input @enderror" value="{{ old('date_borrowwed') }}" >
                                <span class="error-data">@error('date_borrowwed'){{$message}}@enderror</span>
                            </div>
            
    
                        </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Bekor qilish</button>
          <button type="submit" class="btn btn-success" id="">{{__('msg.Create')}}</button>
        </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal edit-->

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
            <div id="for-preloader-update"></div>
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
    $(document).ready(function () {
        $("#dashboard_datatable").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "paging": false
            
        });

        $('form #date_borrowwedId').datetimepicker({
            format: 'Y-M-D'
        });
        $('form #date_borrowwedId_edit').datetimepicker({
            format: 'Y-M-D'
        });
        $('form #date_returnId_edit').datetimepicker({
            format: 'Y-M-D'
        });
        $('#create_borowwing').on('click', function (e){
            e.preventDefault();
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
            $.get('/admin/borowwing/students', function (data) {
                let studentsDataCreate = data['students'];
                for (let i in studentsDataCreate) {
                    $('#students_create').append('<option value=' + studentsDataCreate[i].id + '>' + studentsDataCreate[i].first_name + ' ' + studentsDataCreate[i].last_name +'</option>');
                }
            });
            $.get('/admin/borowwing/books', function (data) {
                
                let booksDataCreate = data['books'];
                for (let i in booksDataCreate) {
                    $('#title_id').append('<option value=' + booksDataCreate[i].id + '>' + booksDataCreate[i].title + '(' + booksDataCreate[i].book_count + ')'+'</option>');
                }
            });
        });
        //===============================data edit modal============================
        
        $('.index-borowwing .data-borowwing-edit').on('click', function (e){
            e.preventDefault();
            let id = $(this).data('id');
            // console.log(id);
            $('#borowwing_edit_form').attr('action','/admin/borowwing/update/'+id);
            $.ajaxSetup({
                beforeSend: function() {
                    // TODO: show your spinner
                    $("#for-preloader-update").addClass('spinner-border');
                    // $("#for-preloader").css({"witdh":"70px","height":"70px"});
                    $('.modal-body').hide();
                },
                complete: function() {
                    // TODO: hide your spinner
                    $('.modal-body').show();
                    $("#for-preloader-update").removeClass('spinner-border');
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
                
                $('#status option').remove();
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
        
        //===========================delete multimple data=========
        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });

        $('.delete_all').on('click', function(e) {

            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  

            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  

                var check = confirm("Belgilangan qatorlarni o'chirishga ishonchingiz komilmi?");  
                if(check == true){  

                    var join_selected_values = allVals.join(","); 

                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });

                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });

        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });

        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();

            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });

            return false;
        });

    });
</script>
@endsection







