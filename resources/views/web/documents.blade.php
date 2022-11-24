@extends('layouts.landing')

@section('content')
    <section class="page document" id="page-document">
        <div class="container">
            <div class="page-header">
                <ul class="page-path">
                    <li><a href="/" class="page-path-link">Главная</a></li>
                    <li><a href="#" class="page-path-link isActive">Документы</a></li>
                </ul>
                <div class="page-title">
                    <h1>Документы</h1>
                </div>
            </div>
            <div class="page-body">
                <div class="document-block business-block">
                    <p>С момента своего основания в 2011 г. компания прилагает все усилия для расширения зарубежного рынка. Непосредственно в год основания компании было открыто уполномоченное представительство BEST FORTUNE в г. Астана Республики Казахстан во главе с Розой Садвакасовой. </p>
                    <p>Усилиями генерального представительства продукция компании завоевала внимание и одобрение правительства Казахстана. Квалификационный сертификат предприятия представлен ниже.</p>
                    <div class="document-photo">
                        <img src="/landing/img/doc1.png" alt="">
                        <img src="/landing/img/doc2.png" alt="">
                        <img src="/landing/img/doc3.png" alt="">
                        <img src="/landing/img/doc4.png" alt="">
                        <img src="/landing/img/doc5.png" alt="">
                    </div>
                </div>
                <div class="document-block">
                    <ul class="business-list">
                        <a class="business-list-item  business-btn" href="/about">О компании</a>
                        <a class="business-list-item isActive business-btn" href="#">Документы</a>
                        <a class="business-list-item  business-btn" href="/contacts">Контакты</a>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section-extra-block">
        <div class="container">
            <div class="page-body-container">
                <div class="business-block">
                    <ul class="business-list">
                        <a class="business-list-item  business-btn" href="/about">О компании</a>
                        <a class="business-list-item  isActive business-btn" href="#">Документы</a>
                        <a class="business-list-item  business-btn" href="/contacts">Контакты</a>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
