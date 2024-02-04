<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/landing/img/icons/favicon.ico" type="image/x-icon">
    <!-- Styles CSS -->
    <link rel="stylesheet" href="/landing/css/style.css">
    <link rel="stylesheet" href="/landing/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="/landing/css/modal-video.min.css">

    <title>Главная - FORTUNA</title>

</head>
<body>
<!-- Header -->
<header class="header" id="header">
    <div class="container">
        <a href="/" class="logo">
            <img src="/landing/img/icons/logo.svg" alt="">
        </a>
        <nav class="menu header-menu">
            <ul class="menu-list navbar-nav">
                <li class="header-dropdown nav-item dropdown">
                    <a class="menu-list-link nav-link" href="/">
                        Главная
                    </a>
                </li>
                <li class="header-dropdown nav-item dropdown">
                    <a class="menu-list-link nav-link" href="#" id="navbarDarkDropdownMenuLink" role="button" >
                        О компании
                    </a>
                    <ul class="dropdown-menu dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="header-drp-item dropdown-item" href="/about">История</a></li>
                        <li><a class="header-drp-item dropdown-item" href="/documents">Документы</a></li>
                        <li><a class="header-drp-item dropdown-item" href="/contacts">Контакты</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="menu-list-link nav-link" href="/web-news">
                        Новости
                    </a>
                    <!-- <ul class="dropdown-menu dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                      <li><a class="header-drp-item dropdown-item" href="#">Action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Another action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Something else here</a></li>
                    </ul> -->
                </li>
                <li class="nav-item dropdown">
                    <a class="menu-list-link nav-link" href="#" id="navbarDarkDropdownMenuLink2" role="button" >
                        Бизнес
                    </a>
                    <ul class="dropdown-menu dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li><a class="header-drp-item dropdown-item" href="/mp_compressed.pdf" target="_blank">Бизнес с Best Fortune</a></li>
                        <li><a class="header-drp-item dropdown-item" href="/benefits">Преимущества</a></li>
                        <li><a class="header-drp-item dropdown-item" href="/promotion">Награды от компании</a></li>
                        <li><a class="header-drp-item dropdown-item" href="/rules">Этика компании</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="menu-list-link nav-link" href="/products">
                        Продукция
                    </a>
                    <!-- <ul class="dropdown-menu dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                      <li><a class="header-drp-item dropdown-item" href="#">Action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Another action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Something else here</a></li>
                    </ul> -->
                </li>
                <li class="nav-item dropdown">
                    <a class="menu-list-link nav-link" href="/main-store">
                        Интернет-магазин
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="menu-list-link nav-link" href="/contacts">
                        Контакты
                    </a>
                    <!-- <ul class="dropdown-menu dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                      <li><a class="header-drp-item dropdown-item" href="#">Action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Another action</a></li>
                      <li><a class="header-drp-item dropdown-item" href="#">Something else here</a></li>
                    </ul> -->
                </li>
            </ul>
            <!-- <ul class="menu-list" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <li><a href="about.html" class="menu-list-link text-medium1">О продrewqукте</a></li>
              <li><a href="owner.html" class="menu-list-link text-medium1">Об основателе</a></li>
              <li><a href="news.html" class="menu-list-link text-medium1">Новости</a></li>
              <li><a href="academy.html" class="menu-list-link text-medium1">Бизнес академия</a></li>
              <li><a href="start-business.html" class="menu-list-link text-medium1">Начать бизнес</a></li>
              <li><a href="#" class="menu-list-link text-medium1">Цели</a></li>
            </ul> -->
            <a  href="/login" class="btn-login-adap btn1">
                Войти
                <img src="/landing/img/icons/ic-login.svg" alt="">
            </a>
        </nav>
        <a href="/login" class="btn-login btn1">
            Войти
            <img src="/landing/img/icons/ic-login.svg" alt="">
        </a>
        <div class="header-burger">
            <span></span>
        </div>
    </div>
</header>



@yield('content')


<!-- Footer -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-block">
            <div class="logo-block">
                <a href="index.html" class="logo-black">
                    <img src="/landing/img/icons/logo.svg" alt="">
                </a>
                <p>Здоровье от природы в каждую семью!</p>
            </div>
            <nav class="menu">
                <div class="menu-items">
                    <div class="menu-title ">
                        <h3>Информация</h3>
                    </div>
                    <ul class="menu-list">
                        <li>
                            <div class="menu-list-item">
                                <a href="/benefits" class="menu-list-link text-medium1">Наши преимущества</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="/mp_compressed.pdf" target="_blank" class="menu-list-link text-medium1">Бизнес с Best Fortune</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="/about" class="menu-list-link text-medium1">О компании</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="/business" class="menu-list-link text-medium1">Бизнес</a>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="menu-items">
                    <div class="menu-title">
                        <h3>Дополнительно</h3>
                    </div>
                    <ul class="menu-list">
                        <li>
                            <div class="menu-list-item">
                                <a href="/documents" class="menu-list-link text-medium1">Дипломы и сертификаты</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="/products" class="menu-list-link text-medium1">Венера и флора</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="web-news" class="menu-list-link text-medium1">Новости</a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="/contacts" class="menu-list-link text-medium1">Контакты</a>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="menu-items">
                    <div class="menu-title">
                        <h3>Дополнительно</h3>
                    </div>
                    <ul class="menu-list">
                        <li>
                            <div class="menu-list-item">
                                <a href="" class="menu-list-link text-medium1">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.2188 15.6089C10.5848 19.9771 16.1168 22.7612 19.4068 19.4851L20.2048 18.687C21.2688 17.625 21.1208 15.8649 19.8848 15.0069C19.1068 14.4649 18.2708 13.8829 17.3468 13.2328C16.3908 12.5608 15.0808 12.6668 14.2508 13.4888L13.3488 14.3849C12.2308 13.6768 11.1248 12.7668 10.0968 11.7408L10.0928 11.7368C9.06681 10.7107 8.1568 9.6027 7.4488 8.48466L8.3448 7.58263C9.16881 6.7546 9.27081 5.44255 8.6008 4.48651C7.9488 3.56248 7.3668 2.72645 6.8268 1.94842C5.96879 0.714376 4.20879 0.566371 3.14678 1.62841L2.34878 2.42644C-0.92523 5.71656 1.85678 11.2448 6.2228 15.6149" stroke="#55CE63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    + 7 701 530 98 71
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="menu-list-item">
                                <a href="" class="menu-list-link text-medium1">
                                    <svg width="28" height="22" viewBox="0 0 28 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.25 3.608C1.25 4.44525 1.66508 5.22866 2.35783 5.69758L10.8309 11.4393C12.7505 12.7398 15.2693 12.7398 17.1889 11.4393L25.6407 5.71033C26.3349 5.24283 26.75 4.45941 26.75 3.62216V3.608C26.75 2.214 25.6195 1.0835 24.2255 1.0835H3.7745C2.3805 1.0835 1.25 2.214 1.25 3.608V3.608Z" stroke="#55CE63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M1.25 3.91699V18.0837C1.25 19.6491 2.51792 20.917 4.08333 20.917H23.9167C25.4821 20.917 26.75 19.6491 26.75 18.0837V3.91699" stroke="#55CE63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2.0802 20.0865L10.7785 11.3882" stroke="#55CE63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M17.2314 11.3979L25.9156 20.0821" stroke="#55CE63" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    bestfortune.kz@mail.com
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="footer-block">
            <div class="rights">
                <p>© 2022 Все правы защищены</p>
            </div>
            <div class="rights">
                <p><a href="http://127.0.0.1:8000/bestfortune/offer-back.pdf" target="_blank">Политика конфиденциальности</a> </p>
            </div>
            <div class="footer-icons">
                <img src="/landing/img/icons/Instagram 1.svg" alt="">
                <img src="/landing/img/icons/Whatsup 1.svg" alt="">
                <img src="/landing/img/icons/Telegram, Square, App, Icon 1.svg" alt="">
                <img src="/landing/img/icons/youtube.svg" alt="">
            </div>
        </div>
    </div>
</footer>

<!-- Scripts JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://unpkg.com/imask"></script>
<script src="/landing/js/modal-video.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="/landing/js/script.js"></script>
</body>
</html>
