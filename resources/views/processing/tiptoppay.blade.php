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
                    <h4 class="text-themecolor m-b-0 m-t-0">Тип оплата  - "TipTop Pay", Сумма оплаты - {{ $cost }} {{ config('marketing.dollar_symbol') }}(В тенге {{ $cost*config('marketing.dollar_course') }} {{ config('marketing.tenge_symbol') }})</h4>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <button id="payButton"  class="btn btn-success m-t-10">Оплатить ${{ $cost }}</button>
                        </div>
                    </div>
                </div>
            </div>
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

@push('scripts')
    <script>
        //document.querySelector('#checkout').onclick = start

        const btn = document.getElementById("payButton")

        const widget = new tiptop.Widget();

        const launchWidget = () => {
            const intentParams = {
                publicTerminalId: "pk_faba06b06e08c3045f83dc1874559", // идентификатор терминала
                description: "Оплата покупки ${{ $cost}}", // описание списания
                paymentSchema: 'Dual', // схема
                currency: "KZT", // валюта
                amount: 100, // сумма {{ ($cost)*config('marketing.dollar_course') }}
                externalId: "{{ $order->id }}", // идентификатор платежа в вашей системе
                restrictedPaymentMethods: [ // список отключенных для данной оплаты методов
                    'GooglePay',
                    'ApplePay'
                ],
                metadata: {
                    basket_id: "some_referrer_123"
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
@endpush

@push('styles')
    <script src="https://widget.tiptoppay.kz/bundles/widget.js"></script>
@endpush
