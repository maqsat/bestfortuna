@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(!is_null($orders))
                        @if($orders->status == 11)
                            <div class="alert alert-warning">
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Квитанция находится на проверке</h3>
                                Статус модерации:  Квитанция отправлено на проверку <br>
                                Сумма оплаты: {{ $orders->amount }} {{ config('marketing.dollar_symbol') }}<br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата отправки: {{ $orders->updated_at }}
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Квитанция отклонена</h3>
                                Статус модерации:  Фейковая квитанция <br>
                                Сумма оплаты: {{ $orders->amount }}  {{ config('marketing.dollar_symbol') }}<br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата ответа: {{ $orders->updated_at }} <br>
                                Квитанция:  <a href="{{asset($orders->scan)}}" target="_blank" class="btn btn-xs btn-danger">Посмотреть</a>
                            </div>
                        @endif
                    @endif


                    @if(is_null($orders) or $orders->status == 12 or $orders->status == 0)
                        @if(!isset($fk))
                            @if(is_null($orders) or $orders->status != 12)
                                <div class="alert alert-danger">
                                    <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Примечание!</h3> Вам необходимо выбрать пакет и оплатить. У вас есть 24 часа чтобы активировать кабинет, по истечению срока ваш кабинет удалится.
                                    <ul>
                                        <li>{{ Auth::user()->name }}</li>
                                        <li>{{ Auth::user()->number }}</li>
                                        <li>Спонсор: {{ \App\User::find(Auth::user()->inviter_id)->name }}</li>
                                        <li>{{ \App\Models\City::find(Auth::user()->city_id)->title }}</li>
                                    </ul>
                                </div>
                            @endif
                        @endif

                            <div class="card">
                                <div class="card-block">
                                    <div class="row pricing-plan">
                                        @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                            <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                                <div class="pricing-box   @if($item->id == 2) featured-plan @endif">
                                                    <div class="pricing-body b-r">
                                                        <div class="pricing-header">
                                                            @if($item->id == 2) <h4 class="price-lable text-white bg-warning"> Popular</h4>@endif
                                                            <h4 class="text-center">{{ $item->title }}</h4>
                                                            <h2 class="text-center"><span class="price-sign">{{ config('marketing.dollar_symbol') }}</span>{{ ($item->cost+$item->old_cost) }}</h2>
                                                        </div>
                                                        <div class="price-table-content">
                                                            <div class="price-row"><i class="fa fa-product-hunt"></i> {{ $item->pv }} BM</div>
                                                            <div class="price-row"><i class="fa fa-money"></i> {{ $item->cost }}$ стоимость пакета</div>
                                                            <div class="price-row"><i class="fa fa-money"></i> {{ $item->old_cost }}$ складской сбор(5%)</div>
                                                            <div class="price-row"><i class="fa fa-money"></i> {{ $item->income }}</div>
                                                            <div class="price-row"><i class="fa fa-star"></i> {{ $item->statusName->title }}</div>
                                                            <div class="price-row"><i class="fa fa-shopping-basket"></i> {{ $item->goods }}</div>
                                                            <div class="price-row">
                                                                <a href="/pay-types?package={{ $item->id }}">
                                                                    <button class="btn btn-success waves-effect waves-light m-t-20">Выбрать пакет и перейти на оплату</button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                    @endif
                </div>
            </div>
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
