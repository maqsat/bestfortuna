@extends('layouts.landing')

@section('content')
    <section class="page business" id="page-business">
        <div class="container">
            <div class="page-header">
                <ul class="page-path">
                    <li><a href="#" class="page-path-link">Главная</a></li>
                    <li><a href="#" class="page-path-link isActive">Контакты</a></li>
                </ul>
                <div class="page-title">
                    <h1>Контакты</h1>
                </div>
            </div>
            <div class="page-body">
                <div class="contacts-block business-block">
                    <span class="contacts-mintext section2-block-mintext">Адрес в г. Астана: </span>
                    <p class="contacts-text">КУБРИНА 20/1.ВП6</p>
                    <span class="contacts-mintext section2-block-mintext">Адрес в г Гонконге:  </span>
                    <p class="contacts-text">UNIT 04,7F BRIGHT WAY TOWER NO.33 MONG KOK RD</p>
                    <span class="contacts-mintext section2-block-mintext">Номер телефона :  </span>
                    <p class="contacts-text">+ 7 701 530 98 71</p>
                    <span class="contacts-mintext section2-block-mintext">Электронная почта:  </span>
                    <p class="contacts-text">gbfi.net2021@gmail.com</p>
                </div>
                <div class="contacts-block business-block">
                    <ul class="business-list">
                        <a class="business-list-item  business-btn" href="/about">О компании</a>
                        <a class="business-list-item  business-btn" href="/documents">Документы</a>
                        <a class="business-list-item isActive  business-btn" href="/contacts">Контакты</a>
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
                        <a class="business-list-item  business-btn" href="/documents">Документы</a>
                        <a class="business-list-item  isActive business-btn" href="#">Контакты</a>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
