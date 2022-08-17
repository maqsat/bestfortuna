@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Календарь активизации</h3>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->

            <div class="row">

                <div class="alert alert-success">
                    <h3 class="text-success"><i class="fa fa-check-circle"></i>  Статус стартового периода</h3>
                    <b>Сумма ежемесячного личного закуп: {{ config('marketing.activation_sum') }} {{ config('marketing.dollar_symbol') }}</b><br>
                    <b>Дата вашей регистрации: {{ Auth::user()->created_at }}</b><br>
                    <b>Дата окончание Стартового периода:  {{ \App\Facades\Balance::getActivationStartDate(Auth::user()->created_at, Auth::user()->id) }}</b><br>
                    После окончание стартового периода вам необходимо совершать ежемесячный личный закуп(активизацию), приобретая продукцию Компании на сумму не более 20 BM в интернет магазине. <br>  Если вы достигли статуса Менеджер в течение стартового периода, то со дня достижение статуса менеджер Вы должны совершать ежемесячный личный закуп(активизацию)
                </div>

                @for($i = 1; $i <= 12;$i++)
                    @php
                        if(date('n') != $i){
                                $activation = DB::table('activations')
                                        ->where('user_id', Auth::user()->id)
                                        ->where('year', '=', date('Y'))
                                        ->where('month', '=', $i)
                                        ->first();
                                if(!is_null($activation)) $activation = $activation->sum;
                                else $activation = 0;
                        }
                        else{
                                $date = new \DateTime();
                                $activation = \App\Facades\Hierarchy::orderSumOfMonth($date,Auth::user()->id);
                        }



                    @endphp
                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-inverse @if($activation >= 20) card-success @else @if($i+1 > date('n')) card-warning @else card-danger  @endif @endif">
                            <div class="box text-center">
                                <h1 class="font-light text-white">{{ $activation }} BM</h1>
                                <h6 class="text-white">{{ \App\Facades\General::getMonthNameById($i-1) }}</h6>
                            </div>
                        </div>
                    </div>
                @endfor

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

@endpush

