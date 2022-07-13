$(document).ready(function () {
  
    // $('.nav-item a').each(function(){
    //     let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
    //     let link = this.href;
    //     if (location == link) {
    //         $(this).parent().addClass('active');
    //     }
    // })

  //   $('.nav-item a').each(function(){
  //     let browserlocation = window.location.protocol + '//' + window.location.host + window.location.pathname;
  //     let alink = this.href;
  //     if (browserlocation == alink) {
  //         // $(this).parent().addClass('menu-open');
  //         $(this).addClass('active');
  //         $(this).parent().addClass('nav-item-menu-active');
  //         $('.nav-item-menu-active').parent().addClass('nav-menu-active');
  //         $('.nav-menu-active').parent().addClass('nav-nav-item-menu-active menu-open');
  //         $('.nav-nav-item-menu-active>a').addClass('active');

  //     }
  // });
  
// $('#dashboard-datatable').DataTable();



      // $('form #create_and_continue').on('click',function(e){
      //   // alert("sadas");
      //   $.ajaxSetup({
      //       headers: {
      //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //       }
      //   });
      //   e.preventDefault();
      //   let title = $("#titleId").val();
      //   let author = $("#authorId").val();
      //   let publisher = $("#publisherId").val();
      //   let book_code = $("#book_codeId").val();
      //   let book_count = $("#book_countId").val();
      //   // alert(comment)
      //   $.ajax({
      //       type: "POST", 
      //       dataType: "json", 
      //       url: "/admin/book/store", 
      //       data: {
      //           title:title,
      //           author:author,
      //           publisher:publisher,
      //           book_code:book_code,
      //           book_count:book_count,
      //       },
      //       success: function(data) {
      //         $("#book_form")[0].reset();
      //           // console.log("success");
      //             // $("form input[type='text']").val('');
      //             // $("form input[type='number']").val('');
      //       },
      //       error: function(error){
      //           console.log(error);
      //           console.log('xatolik');
      //           // alert('xatolik');
      //       }

      //   });


      // });
  
  $('body #dashboard_datatable .delete-data-item').on('click',function (e) {
    // alert('asdsa');
    // e.preventDefault();
    
    if (!confirm("Do asdayou want to delete")){
          return false;
      }else{
        return true;
      }   
  
  });


  $('#previewFile').on('change',function (e) {
    let file = $("input[type=file]").get(0).files[0];
    if (file) {
      let reader = new FileReader();
      reader.onload = function () {
        $('#previewImg').attr("src",reader.result);
      }
      reader.readAsDataURL(file);
    }

  });


  $('#previewFile_oz').change(function(e){
    let reader = new FileReader();
    reader.onload = (e) => { 
        $('#previewImg_oz').attr('src', e.target.result); 
    }
    reader.readAsDataURL(this.files[0]); 
});
    

$('#previewFile_ru').change(function(e){
  let reader = new FileReader();
  reader.onload = (e) => { 
      $('#previewImg_ru').attr('src', e.target.result); 
  }
  reader.readAsDataURL(this.files[0]); 
});

$('#previewFile_en').change(function(e){
  let reader = new FileReader();
  reader.onload = (e) => { 
      $('#previewImg_en').attr('src', e.target.result); 
  }
  reader.readAsDataURL(this.files[0]); 
});

 
    












});