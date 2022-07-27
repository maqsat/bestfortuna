@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

                @if(!is_null($move_status))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Поздравляем, У вас новый статус <b>{{ \App\Models\Status::whereId($move_status->status_id)->first()->title }}</b></h3>
                    </div>
                @endif

                @foreach($not_cash_bonuses as $item)
                    @if($item->type == 'travel_bonus')
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Поздравляем, Happy Travel!</h3> За закрытие статусов, начиная с золота, Вы
                            получаете путевку в экзотические страны мира, за счет компании!
                        </div>
                    @endif

                    @if($item->type == 'status_no_cash_bonus')
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Поздравляем, Бонус признания!</h3> За достижение определенного статуса,
                            компания премирует партнера вознаграждением: VIP подарок от компании
                        </div>
                    @endif
                @endforeach

                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th colspan="2">Информация</th>
                                            <th>Cпонсор</th>
                                            <th>Пакет</th>
                                            <th>Статус</th>
                                            <th>Товарооборот</th>
                                            <th>Баланс</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><span class="round"><img src="{{Auth::user()->photo}}" alt="user" width="50" class="home-img" /></span></td>
                                            <td>
                                                <h6>{{ $user->name }}(Ваш ID:  {{Auth::user()->id_number}})</h6>
                                                <small class="text-muted">{{ $user->number }}</small>
<!--                                                <small class="text-muted">Номер телефона</small>
                                                <h6>{{ $user->email }}</h6>
                                                <small class="text-muted">Дата регистрации </small>
                                                <h6>{{ $user->created_at }}</h6>-->
                                            </td>
                                            <td>
                                                @if(!is_null(\App\User::find($user->inviter_id)))
                                                    {{ \App\User::find($user->inviter_id)->name }}({{ \App\User::find($user->inviter_id)->id_number }})
                                                @else
                                                    Без спонсора
                                                @endif</td>
                                            <td>@if(!is_null($package)){{ $package->title }}(${{ $package->cost }})@else Без пакета @endif</td>
                                            <td>{{ $status->title }}</td>
                                            <td>{{ $pv_counter_all }}  PV</td>
                                            <td>${{ $balance }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Row -->
            <div class="row">
                <div class="col-lg-12 col-xlg-12 col-md-12">
                    <div class="card">
                        <div class="card-block">
                            <h3 class="card-title">Ваши достижения</h3>
                            <div class="row">
                                <!-- Column -->
                                <div class="col-lg-4 col-xlg-4 col-md-4">
                                    <div class="table-responsive">
                                        <table class="table m-b-0  m-t-30 no-border">
                                            <tbody>
                                            <tr>
                                                <td style="width:90px;"><img src="/monster_admin/assets/images/browser/sketch.jpg" alt="sketch" /></td>
                                                <td style="width:200px;">
                                                    <h6 class="card-subtitle">Ваш статус</h6>
                                                    <h4 class="card-title">{{ $status->title }}</h4>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xlg-8 col-md-8">
                                    <h5 class="m-t-30"><small class="text-muted">осталось до </small><span class="pull-right">{{ $next_status->title }} </span></h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning active progress-bar-striped" role="progressbar" style="width: {{ round($percentage) }}%; height:18px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ round($percentage) }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <hr>
                        </div>
                        <div class="card-block">

                            <div class="row">
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xlg-6">
                                    <div class="card card-inverse card-info">
                                        <div class="box bg-info text-center">
                                            <h1 class="font-light text-white">{{ number_format($pv_counter_left, 0, '', ' ') }}</h1>
                                            <h6 class="text-white">Большая ветка PV</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xlg-6">
                                    <div class="card card-primary card-inverse">
                                        <div class="box text-center">
                                            <h1 class="font-light text-white">{{ number_format($pv_counter_right, 0, '', ' ') }}</h1>
                                            <h6 class="text-white">Обьем всех веток PV</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xlg-6">
                                    <div class="card card-inverse card-danger">
                                        <div class="box text-center">
                                            <h1 class="font-light text-white">{{ count($invite_list) }}</h1>
                                            <h6 class="text-white">Личники</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xlg-6">
                                    <div class="card card-inverse card-warning">
                                        <div class="box text-center">
                                            <h1 class="font-light text-white">{{ $list }}</h1>
                                            <h6 class="text-white">Все партнеры</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if(!is_null($package))
            <div class="row" style="display: none">
                <div class="col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-block timer">
                            <h5>Бонус быстрого старта, до следующего зачисление  осталось: </h5>
                            <p>{{ $quickstart_date }}, {{ trans('app.'.$display_day) }}</p>
                            <ul>
                                <li><span id="days"></span>День</li>
                                <li><span id="hours"></span>Час</li>
                                <li><span id="minutes"></span>Минут</li>
                                <li><span id="seconds"></span>Секунд</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-block timer">
                            <h5>Повторная покупка, до следующего списание осталось:</h5>
                            <ul>
                                <li><span id="days2"></span>День</li>
                                <li><span id="hours2"></span>Час</li>
                                <li><span id="minutes2"></span>Минут</li>
                                <li><span id="seconds2"></span>Секунд</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Реферальная ссылка</h4>
                            <h6 class="card-subtitle" style="display: none">Партнеры будут располагаться в структуре по выбранному <code>типу размещения</code></h6>
                            <div class="button-group" style="display: none">
                                <a href="/home?default_position=1">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 1) btn-info @else btn-danger @endif">@if(Auth::user()->default_position == 1) <i class="fa fa-check"></i> @endifСлева</button>
                                </a>
                                <a href="/home?default_position=0">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 0) btn-info @else btn-danger @endif">@if(Auth::user()->default_position == 0) <i class="fa fa-check"></i> @endifАвтоматически</button>
                                </a>
                                <a href="/home?default_position=2">
                                    <button type="button" class="btn @if(Auth::user()->default_position == 2) btn-info @else btn-danger @endif">@if(Auth::user()->default_position == 2) <i class="fa fa-check"></i> @endifСправа</button>
                                </a>
                            </div>
                            <div class="input-group m-t-15">
                                <input  class="form-control form-control-line" id="post-shortlink" value="{{env('APP_URL', false)}}/register?inviter_id={{ Auth::user()->id }}">
                                <span class="input-group-btn">
                                    <button type="button" id="copy-button" data-clipboard-target="#post-shortlink" class="btn waves-effect waves-light btn-success">Копировать</button>
                                </span>
                            </div>
                            <div class="input-group m-t-15">
                                <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                                <script src="https://yastatic.net/share2/share.js"></script>
                                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,viber,whatsapp,skype,telegram" data-title="Реферальная ссылка от {{ Auth::user()->name }}" data-url="https://nrg-max.com/register?inviter_id={{ Auth::user()->id }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card-title">Вы зарегистрировались без пакета.</h4>
                                <div class="card">
                                    <div class="card-block">
                                        <p>Бизнес в компании начинается с простой регистрации.
                                            Регистрация -это открытие своего личного кабинета в системе, в котором партнер получает
                                            доступ к своим данным о состоянии своей структуры и всех начисленных бонусов.
                                            При регистрации, партнеру необходимо приобрести пакет
                                            маркетинговых инструментов, который дает быстрый старт в бизнесе. Стоимость пакета
                                            <b>{{ env('REGISTRATION_FEE') }}$</b> и оплачивается раз в год.</p>

                                        <h5 class="ma">В эту сумму входят:</h5>
                                            <p>
                                                -  обучающие тренинги по рекрутингу новых партнеров<br>
                                                -  пособие по работе с командой<br>
                                                -  рекламные материалы для печати<br>
                                                -  профессиональная IT-поддержка<br>
                                                -  обучающие тренинги по продажам от действующих практиков<br>
                                                -  мотивационные семинары по личностному росту и семейному счастью<br>
                                                -  уникальная авторская автоворонка<br>
                                            </p>
                                        <a href="/programs" class="btn btn-success">Перейти на апгрейд</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
        <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('styles')
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/css-chart/css-chart.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="/monster_admin/assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="/monster_admin/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="/monster_admin/main/js/dashboard1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>


    @if (session('status') || session('success'))

        <script src="/monster_admin/main/js/toastr.js"></script>
        <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
        <script>
            @if(session('status'))
            $.toast({
                heading: 'Результат запроса',
                text: '{{ session('status') }}',
                position: 'top-right',
                loaderBg:'#ffffff',
                icon: 'warning',
                hideAfter: 60000,
                stack: 6
            });
            @elseif(session('success'))
            $.toast({
                heading: 'Результат запроса',
                text: '{{ session('success') }}',
                position: 'top-right',
                loaderBg:'#ffffff',
                icon: 'success',
                hideAfter: 60000,
                stack: 6
            });
            @endif
        </script>
    @endif

    <script>

        (function(){
            new Clipboard('#copy-button');
        })();

        const second = 1000,
            minute = second * 60,
            hour = minute * 60,
            day = hour * 24;

        let countDown = new Date('{{$quickstart_date}}').getTime(),
            x = setInterval(function() {

                let now = new Date().getTime(),
                    distance = countDown - now;

                document.getElementById('days').innerText = Math.floor(distance / (day)),
                document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
                document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

                //do something later when date is reached
                //if (distance < 0) {
                //  clearInterval(x);
                //  'IT'S MY BIRTHDAY!;
                //}

            }, second);



        const second2 = 1000,
            minute2 = second2 * 60,
            hour2 = minute2 * 60,
            day2 = hour2 * 24;

        let countDown2 = new Date('{{$revitalization_date}}').getTime(),
            x2 = setInterval(function() {

                let now = new Date().getTime(),
                    distance = countDown2 - now;

                document.getElementById('days2').innerText = Math.floor(distance / (day2)),
                    document.getElementById('hours2').innerText = Math.floor((distance % (day2)) / (hour2)),
                    document.getElementById('minutes2').innerText = Math.floor((distance % (hour2)) / (minute2)),
                    document.getElementById('seconds2').innerText = Math.floor((distance % (minute2)) / second2);

                //do something later when date is reached
                //if (distance < 0) {
                //  clearInterval(x);
                //  'IT'S MY BIRTHDAY!;
                //}

            }, second2)
    </script>

    <script>


    </script>

@endpush
