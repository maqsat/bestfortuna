@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="m-b-20">Выберите удобный вид оплаты</h4>
                            <div class="row img-for-pay">
                                <div class="col-lg-2 col-md-6  img-responsive">
                                    <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive " src="/nrg/chek.jpg" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">Чек Kaspi</h4>
                                            <p class="card-text">Прикрепите Скан квитанции к форме</p>
                                            <a href="/pay-prepare?type=manual&@if(!is_null($basket))basket={{ $basket->id }} @endif" class="btn btn-success m-t-10">Оплатить ${{  $all_cost+$all_cost*0.05 }}</a>
                                        </div>
                                    </div>
                                    <!-- Card -->
                                </div>
<!--                                <div class="col-lg-2 col-md-6  img-responsive">
                                    &lt;!&ndash; Card &ndash;&gt;
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/paypost.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">PayPost</h4>
                                            <p class="card-text">В карте должен быть подключен 3D secure</p>
                                            <a href="/pay-prepare?type=paypost&@if(!is_null($basket))basket={{ $basket->id }}@endif" class="btn btn-success m-t-10">Оплатить ${{ $all_cost }}</a>
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

@push('scripts')

    <script src="/monster_admin/main/js/toastr.js"></script>
    <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>

    @if (session('status'))
        <script>
            $.toast({
                heading: 'Пустая корзина!',
                text: '{{ session('status') }}',
                position: 'top-right',
                loaderBg:'#ffffff',
                icon: 'error',
                hideAfter: 60000,
                stack: 6
            });
        </script>
    @endif
@endpush


@push('styles')
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush
