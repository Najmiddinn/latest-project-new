@extends('layouts.mainFrontend')

@section('content')
@if (!empty($models))
<div class="section">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row">

      <!-- section title -->
      <div class="col-md-12">
        <div class="section-title">
          <h3 class="title">Barcha kitoblar</h3>
          
        </div>
      </div>
      <!-- /section title -->

      <!-- Products tab & slick -->
     <div class="book-section">
       
     
      <div class="col-md-12">
        <div class="row">
          <div class="products-tabs">
            <!-- tab -->
            <div id="tab1" class="tab-pane active">
              
              <div class="">
                
                  @foreach ($models as $model)
                  <div class="col-md-3">
                    <div class="product" style="margin: 30px 0">
                      <div class="product-img">
                        <img src="{{asset('/assets-frontend/img/sect_documents-pdf-1.png')}}" alt="">
                        <div class="product-label">
                          <span class="new">{{$model->getCategory['name']}}</span>
                        </div>
                      </div>
                      <div class="product-body">
                        <h3 class="product-name">{{ Str::limit(($model->title),105, '...') }}</h3>
                        <div class="product-btns">
                          <a href="{{ asset('/uploads/books/'.$model->id.'/'.$model->file)}}" class="quick-view product-name">Online Ko'rish</a>
                        </div>
                        <div class="product-btns">
                          {{ round(($model->size/1024)/1024,2) }} mb
                        </div>
                      </div>
                      <div class="add-to-cart">
                        <a href="{{ route('site.book-download',['id'=>$model->id,'filename'=>$model->file]) }}" class="add-to-cart-btn">Yuklab olish</a>
                      </div>
                    </div>
                  </div>
                  

                  @endforeach   
                
                
              </div>
             
              <div id="slick-nav-1" class="products-slick-nav"></div>
            </div>
            <!-- /tab -->
          </div>
        </div>
      </div>
      </div>

      
      <!-- Products tab & slick -->
    </div>
  </div>
</div>
<div class="pagination-book">
  {{$models->links()}}
</div>
@endif
<div id="newsletter" class="section">
</div>
@endsection





