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
                    <h3 class="text-themecolor m-b-0 m-t-0">Лично приглашенные - {{ Auth::user()->name }}(Ваш ID:  {{Auth::user()->id_number}})</h3>
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

                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>ФИО</th>
                                        <th>Статус</th>
                                        <th>Товарооборот</th>
                                        <th>Накопительный бонус</th>
                                        <th>Пакет</th>
                                        <th>Номер</th>
                                        <th>Город</th>
                                        <th>Почта</th>
                                        <th>Дата регистрации</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        <tr @if($item->status == 0) class="text-danger" @endif>

                                            <td>{{ $item->id_number }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @if($item->status == 1)
                                                    {{ \App\Models\Status::find(\App\Models\UserProgram::whereUserId($item->id)->first()->status_id)->title }}
                                                @else
                                                    Не активирован
                                                @endif
                                            </td>
                                            <td>{{ \App\Facades\Hierarchy::pvCounterAll($item->id) }}</td>
                                            <td>{{ \App\Facades\Balance::getIncomeBalance($item->id) }}</td>
                                            <td>@if($item->package_id != 0)  {{ \App\Models\Package::find($item->package_id)->title }} @else Без пакета @endif</td>
                                            <td>{{ $item->number }}</td>
                                            <td>{{ \App\Models\City::where('id', $item->city_id)->first()->title }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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
