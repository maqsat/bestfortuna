@extends('layouts.template')


@php
    $day = \Carbon\Carbon::now()->day;

    if($day == 1) dd('Идет расчет ежемесячного бонуса, регистрация доступно с 2 го числа каждого месца');

@endphp

@section('content')
    <style>
        body, html {
            height: 100%;
        }
    </style>

    <section id="wrapper" class="register-padding">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-block wizard-content">
                        <a href="javascript:void(0)" class="text-center db">
                            <img src="bestfortune/logo.svg" alt="Home" class="login_logo" style="width: 250px;"  /><br/>
                        </a>

                        <div class="row">
                            <div class="col-12">
                                <form  class="validation-wizard wizard-circle">
                                    <!-- Step 1 -->
                                    <h6>О дистрибьюторе</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-md-2">
                                                <input type="hidden" name="program_id" id="program_id" value="1">
                                                <div class="form-group">
                                                    <?php
                                                        if(isset($_GET['inviter_id'])) $user = \App\User::find($_GET['inviter_id']);
                                                        else $user = null;
                                                    ?>
                                                    <label for="inviter_id">Для обработки вашего заказа, введите, пожалуйста, ID спонсора(дистрибьютора) :</label>{{--{{ app('request')->input('inviter_id') }}--}}
                                                    @if(!is_null($user))
                                                        <input type="text" class="form-control required" value="{{ $user->name }} - {{ $user->number }}" disabled>
                                                        <input type="hidden" class="form-control required" id="inviter_id" name="inviter_id" value="{{ $user->id_number }}">
                                                    @else
                                                        <input type="text" class="form-control required"  id="inviter_id" name="inviter_id" value="">
                                                    @endif
                                                    <div class="error-message"></div>
                                                </div>
                                                {{--<div class="form-group">
                                                    <label for="sponsor_id">Для обработки вашего заказа, введите, пожалуйста, ID наставника(уточните у спонсора):</label>
                                                    <input type="number" class="form-control required"  id="sponsor_id" name="sponsor_id" value="{{ app('request')->input('sponsor_id') }}">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="position">Позиция :</label>
                                                    <select class="custom-select form-control required" id="position" name="position">
                                                        <option value="1">Слева</option>
                                                        <option value="2">Справа</option>
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>--}}
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Персональный данные</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-5  offset-1">
                                                <div class="form-group">
                                                    <label for="name">ФИО :</label>
                                                    <input type="text" class="form-control required" id="name" name="name">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="number">Телефон :</label>
                                                    <input type="number" class="form-control required" id="number" name="number">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email : (Поменяйте если у вас есть электонная почта)</label>
                                                    <input type="email" class="form-control required"  id="email" name="email" value="{{ Illuminate\Support\Str::random(15)."@best-fortuna.kz" }}">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Номер документа(ИИН) :</label>
                                                    <input type="text" class="form-control required"  id="iin" name="inn">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label for="package_id">Выберите пол:</label>
                                                        <select class="custom-select form-control required" id="gender" name="gender">
                                                            <option>Не указан</option>
                                                            <option value="1"  @if(old('gender') == 1) selected @endif>Мужской</option>
                                                            <option value="2"  @if(old('gender') == 2) selected @endif>Женский</option>
                                                        </select>
                                                        <div class="error-message"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="birthday">Дата рождения:</label>
                                                    <input type="text" class="form-control required" id="birthday" name="birthday">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Пароль:</label>
                                                    <input type="password" class="form-control required" id="password" name="password">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password_confirmation">Повторите пароль:</label>
                                                    <input type="password" class="form-control required" id="password_confirmation" name="password_confirmation">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 3 -->
                                    <h6>Структура</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-md-2">
                                                <div class="form-group">
                                                    <label for="country_id">Страна :</label>
                                                    <select class="custom-select form-control  required" id="country_id" name="country_id" required>
                                                        <option></option>
                                                        @foreach(\App\Models\Country::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('country_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="position">Ваш город:</label>
                                                    <select class="custom-select form-control required" id="city_id" name="city_id"  required {{--onchange="getOffices(this)"--}}>
                                                        <option>Выберите город</option>
                                                        @foreach(\App\Models\City::where('status',1)->get() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('city_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="position">Офис:</label>
                                                    <select class="form-control form-control-line" name="office_id" id="office_id" required>
                                                        <option>Не указан</option>
                                                        @foreach(\App\Models\Office::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('city_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="checkbox checkbox-primary pull-left">
                                                            <input type="checkbox" id="terms" name="terms" class="required" checked>
                                                            <label for="checkbox-signup">  Регистрируясь на сайте вы подтверждаете, что ознакомились с <a href="/bestfortune/offer-back.pdf"  target="_blank">Публичная оферта</a>. </label>
                                                            <div class="error-message"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 4 -->
                                    <h6>Доставка</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-2">
                                                <div class="form-group">
                                                    <label for="address">Улица, номер дома, номер квартиры :</label>
                                                    <input type="text" class="form-control required" id="address" name="address">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="post_index">Почтовой индекс  :</label>
                                                    <input type="number" class="form-control required" id="post_index" name="post_index">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 5 -->
                                    <h6>Оплата</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-2">
                                                <div class="form-group">
                                                    <div class="alert alert-danger">
                                                        <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Примечание!</h3>
                                                        На следующем этапе вам нужно будеть выбрать пакет и оплатить нужную сумма для активации в программе.
                                                        Не передавайте данные карты третьим лицам.
                                                    </div>
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
    <script>

        var form = $(".validation-wizard").show();

        $(".validation-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> <span class="step-name">#title#</span> ',
            labels: {
                finish: "Submit"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                $('.error-message').html('');
                $('input').removeClass('error');

                if(currentIndex == 0){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);

                    var params = 'program_id='+document.getElementById('program_id').value+'&inviter_id='+document.getElementById('inviter_id').value+'&step='+currentIndex;

                    console.log(params);
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }

                }
                if(currentIndex == 1){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);

                    var params = 'name='+document.getElementById('name').value+'&number='+document.getElementById('number').value+'&email='+document.getElementById('email').value+'&gender='+document.getElementById("gender").selectedIndex+'&birthday='+document.getElementById('birthday').value+'&password='+document.getElementById('password').value+'&password_confirmation='+document.getElementById('password_confirmation').value+'&iin='+document.getElementById('iin').value+'&step='+currentIndex;

                    console.log(params);
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }

                }
                if(currentIndex == 2){

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);

                    /*var params = 'terms='+document.getElementById('terms').value+'&city_id='+document.getElementById("city_id").selectedIndex+'&office_id='+document.getElementById("office_id").selectedIndex+'&country_id='+document.getElementById("country_id").selectedIndex+'&step='+currentIndex;*/
                    var params = 'terms='+document.getElementById('terms').value+'&city_id='+document.getElementById("city_id").selectedIndex+'&country_id='+document.getElementById("country_id").selectedIndex+'&office_id='+document.getElementById("office_id").selectedIndex+'&step='+currentIndex;
                    console.log(params);

                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                console.log(key);
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
                if(currentIndex == 3){

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);
                    var params = 'post_index='+document.getElementById('post_index').value+'&address='+document.getElementById('address').value+'&step='+currentIndex;
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
                if(currentIndex == 4){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);
                    var params = 'step='+currentIndex;
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
            },
            onFinishing: function (event, currentIndex) {
                $.ajax({
                    type: "POST",
                    url: "/register",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        name: document.getElementById('name').value,
                        number: document.getElementById('number').value,
                        iin: document.getElementById('iin').value,
                        email: document.getElementById('email').value,
                        gender: document.getElementById('gender').value,
                        birthday: document.getElementById('birthday').value,
                        password: document.getElementById('password').value,
                        password_confirmation: document.getElementById('password_confirmation').value,
                        program_id: document.getElementById('program_id').value,
                        inviter_id: document.getElementById('inviter_id').value,
                        /*sponsor_id: document.getElementById('sponsor_id').value,
                        position: document.getElementById('position').value,*/
                        terms: document.getElementById('terms').value,
                        city_id: document.getElementById('city_id').value,
                        office_id: document.getElementById('office_id').value,
                        country_id: document.getElementById('country_id').value,
                        address: document.getElementById('address').value,
                        post_index: document.getElementById('post_index').value,
                        /*package_id: document.getElementById('package_id').value*/
                    },
                    success: function(){
                        window.location.href = "/home";
                    },
                    error: function (request, status, error) {
                        alert('Есть ошибка в регистрационнных данных. Попробуйте заново! Напоминаем что причиной ошибки может быть неактивированные пользователи спонсора.');
                    }
                });

                return form.validate().settings.ignore = ":disabled", form.valid()
            }
            , onFinished: function (event, currentIndex) {
                //swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
            }
        }), $(".validation-wizard").validate({
            ignore: "input[type=hidden]"
            , errorClass: "text-danger"
            , successClass: "text-success"
            , highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , errorPlacement: function (error, element) {
                error.insertAfter(element)
            }
            , rules: {
                email: {
                    email: !0
                }
            }
        })


        /*function getOffices(city_id) {
            $.ajax({
                type: "GET",
                url: "/user_offices",
                data: 'city_id='+city_id.value,
                success: function (data) {
                    console.log('Submission was successful.');
                    console.log(data);

                    $('#office_id')
                        .find('option')
                        .remove()
                        .end()
                        .append(data)
                        .val('whatever')
                    ;

                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                },
            });
        }*/
    </script>
@endpush

@section('body-class') login-register @endsection
