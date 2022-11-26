@extends('layouts.landing')

@section('content')
    <section class="page about" id="page-about">
        <div class="container">
            <div class="page-header">
                <ul class="page-path">
                    <li><a href="#" class="page-path-link">Главная</a></li>
                    <li><a href="#" class="page-path-link isActive">О продукте</a></li>
                </ul>
                <div class="page-title">
                    <h1>{{ $product->title }}</h1>
                </div>
            </div>
            <div class="page-body">
                <div class="about-block">
                    <div class="about-main-text">
                        {!! $product->description !!}
                    </div>
                    <div class="about-main-img">
                        <img src="/{{ $product->image1 }}" alt="" class="">
                    </div>
                </div>


<!--                <div class="about-block">
                    <div class="about-main-text">
                        <div class="about-reviews-header">
                            <h3>Отзывы о продукте</h3>
                            <p class="text-medium1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <img src="img/icons/Group.svg" alt="">
                                Оставить свой отзыв
                            </p>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h2>Пока вы не можете оставить отзыв…</h2>
                                            <p>Отзывы доступны зарегистрированным пользователям. (войти/зарегистрироваться)</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn main-btn">Зарегистрироваться</button>
                                            <button type="button" class="btn main-btn">Войти</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="about-reviews-body">
                            <div class="about-reviews-author">
                                <p>Любовь К</p>
                                <span class="section2-block-mintext">11 ноябрь 2022</span>
                            </div>
                            <p>
                                Как открываешь сразу приятный аромат, отличный вкус, просто нет слов, отличная новиночка, всем советую. Теперь это мой любимый вкус! Рекомендую)
                            </p>
                        </div>
                        <div class="about-reviews-body">
                            <div class="about-reviews-author">
                                <p>Тимур В.</p>
                                <span class="section2-block-mintext">11 ноябрь 2022</span>
                            </div>
                            <p>
                                Когда я увидела новинку подумала интересно,что за вкус с кактусом ☺️ Но когда я попробовала я прибывала в огромном шоке от нереального вкуса Это как вообще можно так вкусно сделать ?😀 По вкусу прям такой нежный ,свеженький ,послевкусие киви 🥝 и даже есть семечки 😍 Нереально вкусно я даже не ожидала С коктейлями я худела и у меня прекрасный результат -,13,а сейчас коктейли просто для удобства Кажется уже не могу без них
                            </p>
                        </div>
                        <div class="about-reviews-body">
                            <div class="about-reviews-author">
                                <p>Любовь К</p>
                                <span class="section2-block-mintext">11 ноябрь 2022</span>
                            </div>
                            <p>
                                Как открываешь сразу приятный аромат, отличный вкус, просто нет слов, отличная новиночка, всем советую. Теперь это мой любимый вкус! Рекомендую)
                            </p>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </section>
@endsection
