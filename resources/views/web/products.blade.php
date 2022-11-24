@extends('layouts.landing')

@section('content')
    <section class="page products" id="page-products">
        <div class="container">
            <div class="page-header">
                <ul class="page-path">
                    <li><a href="#" class="page-path-link">Главная</a></li>
                    <li><a href="#" class="page-path-link">Бизнес</a></li>
                </ul>
                <div class="page-title">
                    <h1>Продукция</h1>
                </div>
            </div>
            <div class="page-body">
                <div class="product-block">
                    <div class="tab1 nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="tab1-link nav-link active" id="nav-1-tab" data-bs-toggle="tab" data-bs-target="#nav-1" type="button" role="tab" aria-controls="nav-1" aria-selected="true">Серия для мужчин и женщин</button>
                        <button class="tab1-link nav-link" id="nav-2-tab" data-bs-toggle="tab" data-bs-target="#nav-2" type="button" role="tab" aria-controls="nav-2" aria-selected="false">Серия для китайской медицины</button>
                        <button class="tab1-link nav-link" id="nav-3-tab" data-bs-toggle="tab" data-bs-target="#nav-3" type="button" role="tab" aria-controls="nav-3" aria-selected="false">Продукция нанотехнологий</button>
                        <button class="tab1-link nav-link" id="nav-4-tab" data-bs-toggle="tab" data-bs-target="#nav-4" type="button" role="tab" aria-controls="nav-4" aria-selected="false">Наборы продукций</button>
                    </div>
                    <div class="tab1-content tab-content" id="nav-tabContent">
                        <div class="tab1-pane tab-pane fade active show" id="nav-1" role="tabpanel" aria-labelledby="nav-1-tab">
                            <div class="section0-body section-body">
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product2.png" alt="">
                                    </div>
                                    <p> <strong>Гепабаланс 3</strong> </p>
                                </a>
                                <a href="about.html" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product3.png" alt="">
                                    </div>
                                    <p> <strong>Энергия Баланс 3</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product2.png" alt="">
                                    </div>
                                    <p> <strong>Гепабаланс 3</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product3.png" alt="">
                                    </div>
                                    <p> <strong>Энергия Баланс 3</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product2.png" alt="">
                                    </div>
                                    <p> <strong>Гепабаланс 3</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product3.png" alt="">
                                    </div>
                                    <p> <strong>Энергия Баланс 3</strong> </p>
                                </a>
                                <a href="" class="section0-body-wrapper-item text-medium1">
                                    <div class="section0-body-item">
                                        <img src="/landing/img/product.png" alt="">
                                    </div>
                                    <p> <strong>Кальция хелат</strong> </p>
                                </a>
                                <div class="news-pagination-block">
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
                                </div>
                            </div>
                        </div>
                        <div class="tab1-pane tab-pane fade" id="nav-2" role="tabpanel" aria-labelledby="nav-2-tab">

                        </div>
                        <div class="tab1-pane tab-pane fade" id="nav-3" role="tabpanel" aria-labelledby="nav-3-tab">
                            <div class="about-tab-content-1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
