@extends('layouts.profile')

@php
    $day = \Carbon\Carbon::now()->day;

    if($day == 1) dd('Идет расчет ежемесячного бонуса, регистрация доступно с 2 го числа каждого месца');

@endphp

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-block printableArea">
                        <h3><b>Корзина</b> <span class="pull-right"># {{ $basket->id }}</span></h3>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <address>
                                        <h3> &nbsp;<b class="text-danger">{{ Auth::user()->name }}</b></h3>
                                        <p class="text-muted m-l-5">
                                            @if(intval(Auth::user()->type))
                                            @else
                                                {{ \App\Models\Package::find(Auth::user()->package_id)->title }}, {{ \App\Models\Status::find($user_program->status_id)->title }}
                                                <br/> {{ \App\Models\City::whereId(Auth::user()->city_id)->first()->title }},{{ \App\Models\Country::whereId(Auth::user()->country_id)->first()->title }}
                                                <br/> {{ Auth::user()->post_index }}, {{ Auth::user()->address }}
                                                <br/> {{ Auth::user()->number }}</p>
                                            @endif
                                    </address>
                                </div>
                                <div class="pull-right text-right">
                                    <address>
                                        <h4 class="font-bold">Kazpost</h4>
                                        <p class="m-t-30"><b>Дата заказа:</b> <i class="fa fa-calendar"></i> {{ date('Y-m-d') }}</p>
                                        <p><b>Доставим до:</b> <i class="fa fa-calendar"></i> {{ date('Y-m-d', time() + 86400*14) }}</p>
                                    </address>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Наименование</th>
                                            <th class="text-right">Количество</th>
                                            <th class="text-right">Цена за одного</th>
                                            <th class="text-right">Сумма</th>
                                            <th class="text-right">Удалить</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all = 0;
                                       /* $all_cv = 0;*/
                                        $all_pv = 0;
                                        ?>
                                        @foreach($goods as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ $key+1 }}</td>
                                                <td>{{($item->is_client)?'Контакты '.$item->title:$item->title}}</td>
                                                <td  class="text-right">
                                                    @if( is_null($item->is_client) )
                                                        <button style="background:transparent;border:none;" onclick="addBasket(this,{{ $item->id }},{{ Auth::user()->id }},{{$key}},true,false,false)">
                                                            <i  class="fa fa-plus-circle" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                    <span id="q{{$item->id}}">{{ $item->quantity }}</span>
                                                    @if( is_null($item->is_client) )
                                                        <button class="minusButton" style="background:transparent;border:none;" onclick="addBasket(this,{{ $item->id }},{{ Auth::user()->id }},{{$key}},false,true,false)">
                                                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="text-right"> ${{ $item->partner_cost }} </td>
                                                <td class="text-right"> $<span id="product_sum{{$item->id}}">{{ $item->partner_cost*$item->quantity }} </span></td>
                                                <td class="text-right">
                                                    <button style="background:transparent;border:none;" onclick="addBasket(this,{{ $item->id }},{{ Auth::user()->id }},{{$key}},false,false,true)">
                                                        <i  class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                            $all += $item->partner_cost*$item->quantity;
                                           /* $all_cv += $item->cv*$item->quantity;*/
                                            $all_pv += $item->pv*$item->quantity;
                                            ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="pull-right m-t-30 text-right">
                                    <p>Сумма: $<span id="total_sum">{{ $all }}</span></p>
                                    <p>Складской сбор(5%): $<span id="storage_sum">{{$all*0.05}}</span></p>
                                    <hr>
                                    <h3><b>Итого :</b> $<span id="itogo">{{ $all+$all*0.05 }}</span></h3>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                                @if(!isset($_GET['id']))
                                    <div class="text-right">
                                        <a class="btn btn-danger" href="/pay-types?basket={{ $basket->id }}"> Оплатить </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*increase - увеличить товар на 1 еденицу
        * decrease - уменшение товара на 1 еденицу*/



        function addBasket(button,good,user,botton,increase,decrease,is_delete) {
            $(".minusButton").attr("disabled", true);
            var row_order=$(button).closest('tr').index();

            $.ajax({
                type:'POST',
                url:'/basket',
                data: {
                    botton:row_order,
                    good_id:good,
                    user_id:user,
                    is_increase:increase,
                    is_decrease:decrease,
                    is_delete:is_delete
                },
                success:function(data){
                    if(increase) {
                        /*quantity of product*/
                        $("#q" + data["goods"][row_order]["id"]).html(data["quantity"]);
                        $("#pv" + data["goods"][row_order]["id"]).html(data["product_total_pv"]);
                        /*total price of product*/
                        $("#product_sum" + data["goods"][row_order]["id"]).html(data["product_total_sum"]);
                        $("#all_pv").html(parseInt($("#all_pv").text()) + parseInt(data["pv"]));
                        /*Total sum*/
                        $("#total_sum").html(parseInt($("#total_sum").text()) + parseInt(data["cost"]));
                        $("#storage_sum").html(parseInt($("#total_sum").text())*0.05);
                        /*Total sum tg*/
                        $("#total_sum_tg").html(parseInt($("#total_sum_tg").text()) + parseInt(data["cost"]) * 385);
                    }
                    else if(decrease){
                        if(data["quantity"]==0) {
                            $(button).closest('tr').remove();
                        }
                        /*quantity of product*/
                        $("#q" + data["goods"][row_order]["id"]).html(data["quantity"]);
                        $("#pv" + data["goods"][row_order]["id"]).html(data["product_total_pv"]);
                        /*total price of product*/
                        $("#product_sum" + data["goods"][row_order]["id"]).html(data["product_total_sum"]);
                        $("#all_pv").html(parseInt($("#all_pv").text()) - parseInt(data["pv"]));
                        /*Total sum*/
                        $("#total_sum").html(parseInt($("#total_sum").text()) - parseInt(data["cost"]));
                        $("#storage_sum").html();
                        /*Total sum tg*/
                        $("#total_sum_tg").html(parseInt($("#total_sum_tg").text()) - parseInt(data["cost"]) * 385);
                    }
                    else if(is_delete){
                        $(button).closest('tr').remove();
                        $("#all_pv").html(parseInt($("#all_pv").text()) - parseInt(data["product_total_pv"]));
                        /*Total sum*/
                        $("#total_sum").html(parseInt($("#total_sum").text()) - parseInt(data["product_total_sum"]));
                        /*Total sum tg*/
                        $("#total_sum_tg").html(parseInt($("#total_sum_tg").text()) - parseInt(data["product_total_sum"]) * 385);
                    }
                    /*Itogo*/
                    $("#itogo").html(parseInt(($("#total_sum")).text()) + parseInt($("#total_sum").text())*0.05);

                    $(".minusButton").attr("disabled", false);

                }
            });


        }
    </script>
@endpush

