@extends('layouts.mainFrontend')

@section('content')
@if (!empty($models))
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="section-title">
          <h3 class="title">{{count($models)}} ta kitob topildi</h3>
        </div>
      </div>
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
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
<div class="pagination-book">
    {{$models->appends($data)->links()}}
</div>
@else
<div class="section-title">
    <div class="section-title">
        <h3 class="title">kiritilgan kalit so'z bo'yicha xech narsa topilmadi!</h3>
    </div>
</div>
@endif
<div id="newsletter" class="section">
</div>
@endsection





