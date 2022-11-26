@extends('layouts.landing')

@section('content')
    <section class="page products" id="page-products">
        <div class="container">
            <div class="page-header">
                <ul class="page-path">
                    <li><a href="/" class="page-path-link">Главная</a></li>
                    <li><a href="#" class="page-path-link">Продукция</a></li>
                </ul>
                <div class="page-title">
                    <h1>Продукция</h1>
                </div>
            </div>
            <div class="page-body">
                <div class="product-block">
                    <div class="tab1 nav nav-tabs" id="nav-tab" role="tablist">
                        <a href="/products?tag_id=1" class="tab1-link nav-link  @if($tag_id == 1) active @endif" >Серия для мужчин и женщин</a>
                        <a href="/products?tag_id=2"  class="tab1-link nav-link @if($tag_id == 2) active @endif">Серия для китайской медицины</a>
                        <a href="/products?tag_id=3"  class="tab1-link nav-link @if($tag_id == 3) active @endif">Продукция нанотехнологий</a>
                        <a href="/products?tag_id=4"  class="tab1-link nav-link @if($tag_id == 4) active @endif">Наборы продукций</a>
                    </div>
                    <div class="tab1-content tab-content" id="nav-tabContent">
                        <div class="tab1-pane tab-pane fade active show" id="nav-1" role="tabpanel" aria-labelledby="nav-1-tab">
                            <div class="section0-body section-body">

                                @foreach($products as $item)

                                    <a href="/web-product/{{ $item->id }}" class="section0-body-wrapper-item text-medium1">
                                        <div class="section0-body-item">
                                            <img src="/{{ $item->image1 }}" alt="">
                                        </div>
                                        <p> <strong>{{ $item->title }}</strong> </p>
                                    </a>

                                @endforeach

                            </div>
                        <!--                            <div class="news-pagination-block">
                                <div class="news-pagination page-pag">
                                    <a href="#" class="page-pag-left-btn">
                                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 9L1 5L5 1" stroke="#9C9C9C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <div class="page-pag-body">
                                        <a href="#" class="page-page-number">1</a>
                                        <a href="#" class="page-page-number isActive">2</a>
                                        <a href="#" class="page-page-number">3</a>
                                        <a href="#" class="page-page-number">...</a>
                                        <a href="#" class="page-page-number">20</a>
                                    </div>
                                    <a href="#" class="page-pag-right-btn">
                                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 9L5 5L1 1" stroke="#9C9C9C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
