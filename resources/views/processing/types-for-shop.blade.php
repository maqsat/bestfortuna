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
                               <div class="col-lg-2 col-md-6  img-responsive">
                                   <!-- Card -->
                                    <div class="card">
                                        <img class="card-img-top img-responsive" src="/nrg/tiptop.png" alt="Card image cap">
                                        <div class="card-block">
                                            <h4 class="card-title">TipTop Pay</h4>
                                            <p class="card-text">На карте должен быть подключен 3D secure</p>
                                            <button id="payButton"  class="btn btn-success m-t-10">Оплатить ${{ $all_cost+$all_cost*0.05 }}</button>
                                        </div>
                                    </div>
                                   <!-- Card -->
                                </div>


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

    <script>
        //document.querySelector('#checkout').onclick = start

        const btn = document.getElementById("payButton")

        const widget = new tiptop.Widget();

        const launchWidget = () => {
            const intentParams = {
                publicTerminalId: "pk_faba06b06e08c3045f83dc1874559", // идентификатор терминала
                description: "Оплата покупки ${{ $all_cost+$all_cost*0.05 }}", // описание списания
                paymentSchema: 'Dual', // схема
                currency: "KZT", // валюта
                amount: 100, // сумма {{ ($all_cost+$all_cost*0.05)*config('marketing.dollar_course') }}
                externalId: "{{ $basket->id }}", // идентификатор платежа в вашей системе
                restrictedPaymentMethods: [ // список отключенных для данной оплаты методов
                    'GooglePay',
                    'ApplePay'
                ],
                metadata: {
                    referrerId: "some_referrer_123"
                },
                successRedirectUrl: "https://best-fortuna.kz/main-store?success=true",
                failRedirectUrl: "https://best-fortuna.kz/main-store?success=false",
                retryPayment: true,
                receiptEmail: "{{ Auth::user()->email }}",
                tokenize: false,
                emailBehavior: "Required",
            };

            widget.start(intentParams).then(function(widgetResult) {
                console.log('result', widgetResult);
            }).catch(function(error) {
                console.log('error', error);
            });
        }

        btn.addEventListener('click', launchWidget)
    </script>


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
    <script src="https://widget.tiptoppay.kz/bundles/widget.js"></script>
@endpush
