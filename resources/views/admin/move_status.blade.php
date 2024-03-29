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
                    <h3 class="text-themecolor m-b-0 m-t-0">Изменение статусов</h3>
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
                                        <th>Тип</th>
                                        <th>Пользователь</th>
                                        <th>Новый статус</th>
                                        <th>Дата</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($all as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->id }}</td>
                                            <td><span class="label label-rouded label-success">достижение статуса</span></td>
                                            <td class="txt-oflo">{{ \App\User::find($item->user_id)->name }}</td>
                                            <td><span class="label label-rouded label-warning">{{ \App\Models\Status::find($item->status_id)->title  }}</span></td>
                                            <td class="txt-oflo">{{ $item->created_at }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $all->links() }}
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
