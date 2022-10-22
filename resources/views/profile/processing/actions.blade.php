<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block p-b-0 out">
                <h4 class="card-title">Доступные операции</h4>
                <p>Льгота: {{ Hierarchy::getBenefit(Auth::user()->benefit)->title }} - {{ Hierarchy::getBenefitPercentage(Auth::user()->id) }}% до <b>{{ Auth::user()->benefit_time }}</b>, если срок истек вам нужно повторная модерация</p>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="">Вывод наличными</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#checkingAccount" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="">Вывод на  Расчетный счет(ИП)</span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active   p-20" id="home2" role="tabpanel">
                        <form {{--action="/processing"--}} action="/request" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-control-feedback text-danger" id="demo"></div>
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" id="sum"  max="{{ $balance }}" required onkeyup="myFunction()">
                                        <input type="text"  name="login" class="form-control" placeholder="Номер телефона и карты Kaspi" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                    <div class="tab-pane  p-20" id="checkingAccount" role="tabpanel">
                        <form {{--action="/processing"--}} action="/request" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="hidden" value="checking-account" name="withdrawal_method">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="login" class="form-control" placeholder="Номер расчетного счёта" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function myFunction() {
        var sum = document.getElementById("sum").value;
        var x = {{ Hierarchy::getBenefitPercentage(Auth::user()->id) }}/100;
        var total_sum = sum - (sum*x);
        document.getElementById("demo").innerHTML = "Сумма после удержание налогов: " + total_sum + "$";
    }
</script>
