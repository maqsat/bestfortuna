@extends('layouts.admin')

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
                    <h3 class="text-themecolor m-b-0 m-t-0">Комиссионные по статусам</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="display nowrap table table-hover" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Статус</th>
                                        <th>Сумма</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Кэшбек</td>
                                        <td>{{ number_format(round(\App\Facades\Balance::getBalanceByStatus('cashback')), 0, '', ' ')  }}$</td>
                                        <td><a href="">Подробнее</a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Матчинг бонус</td>
                                        <td>{{ number_format(round(\App\Facades\Balance::getBalanceByStatus('matching_bonus')), 0, '', ' ')  }}$</td>
                                        <td><a href="">Подробнее</a></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Бонус за бинар</td>
                                        <td>{{ number_format(round(\App\Facades\Balance::getBalanceByStatus('turnover_bonus')), 0, '', ' ')  }}$</td>
                                        <td><a href="">Подробнее</a></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Статусный бонус</td>
                                        <td>{{ number_format(round(\App\Facades\Balance::getBalanceByStatus('status_bonus')), 0, '', ' ')  }}$</td>
                                        <td><a href="">Подробнее</a></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Реферальный бонус</td>
                                        <td>{{ number_format(round(\App\Facades\Balance::getBalanceByStatus('invite_bonus')), 0, '', ' ')  }}$</td>
                                        <td><a href="">Подробнее</a></td>
                                    </tr>
                                    </tbody>
                                </table>
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
    <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('styles')
    <style>
        .table td, .table th {
            padding: .25rem .15rem;
        }
    </style>
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush


@push('scripts')
    @if ($errors->has('login'))

        <script src="/monster_admin/main/js/toastr.js"></script>
        <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
        @foreach ($errors->all() as $error)
            <script>
                $.toast({
                    heading: '{{ __('app.errors in login') }}',
                    text: '{{ $errors->first('login') }}',
                    position: 'top-left',
                    loaderBg:'#ffffff',
                    icon: 'warning',
                    hideAfter: 30000,
                    stack: 6
                });
            </script>
        @endforeach
    @endif
@endpush
