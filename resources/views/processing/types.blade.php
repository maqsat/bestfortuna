@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
                    <?php $all_cost = ($package->cost+$package->old_cost)?>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="m-b-20">Выберите удобный вид оплаты</h4>
                            <!-- Row -->
                            <div class="row img-for-pay">
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive " src="/nrg/chek.jpg" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Чек Kaspi</h4>
                                            <p class="card-text">Прикрепите Скан квитанции к форме</p>
                                            <a href="/pay-prepare?type=manual&@if(!is_null($package))package={{ $package->id }} @endif" class="btn btn-success m-t-10">Оплатить {{ config('marketing.dollar_symbol') }}{{ $all_cost }}</a>
                                            <p class="card-text">В тенге {{ $all_cost*config('marketing.dollar_course') }} {{ config('marketing.tenge_symbol') }}</p>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
<!--                                <div class="col-lg-2 col-md-6  img-responsive">
                                    &lt;!&ndash; Card &ndash;&gt;
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/paypost.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Оплатить PayPost</h4>
                                            <p class="card-text">В карте должен быть подключен 3D secure</p>
                                            <a href="/pay-prepare?type=paypost&@if(!is_null($package))package={{ $package->id }}@endif" class="btn btn-success m-t-10">Оплатить {{ config('marketing.dollar_symbol') }}{{ $all_cost }}</a>
                                            <p class="card-text">В тенге {{ $all_cost*config('marketing.dollar_course') }} {{ config('marketing.tenge_symbol') }}</p>
                                        </div>
                                    </div>
                                    &lt;!&ndash; Card &ndash;&gt;
                                </div>-->

                            </div>
                            <!-- Row -->
                        </div>
                    </div>
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
