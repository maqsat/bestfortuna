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

                @for($i = 0; $i < 12;$i++)
                    @php
                        $activation = \App\Models\Order::whereUserId(Auth::user()->id)
                            ->whereYear('created_at', '=', date('Y'))
                            ->whereMonth('created_at', '=', $i+1)
                            ->sum('amount')
                    @endphp
                    <div class="col-md-6 col-lg-3 col-xlg-3">
                        <div class="card card-inverse @if($activation >= 20) card-success @else @if($i+1 > date('n')) card-warning @else card-danger  @endif @endif">
                            <div class="box text-center">
                                <h1 class="font-light text-white">{{ $activation }} BM</h1>
                                <h6 class="text-white">{{ \App\Facades\Hierarchy::getMonthNameById($i) }}</h6>
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

