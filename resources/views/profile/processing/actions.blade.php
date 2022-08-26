<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block p-b-0">
                <h4 class="card-title">Доступные операции</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Вывод наличными</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#checkingAccount" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Вывод на  Расчетный счет(ИП)</span>
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
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="login" class="form-control" placeholder="Номер карты" required>
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
