

@extends('layouts.profile')

@section('in_content')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">

<!-- partial:index.partial.html -->
<div class="body user-body user-scroll">
    <div class="user-tree">
        <ul class="parent-tree">
            <li>
                <a href="javascript:void(0);">
                    <div class="member-view-box">
                        <div class="member-image">
                            <img src="{{$user->photo}}" alt="" class="bg-red">
                        </div>
                        <div class="member-details">
                            <h6>{{ $user->name }}</h6>
                            <p>{{ \App\Facades\Hierarchy::getStatusName($user->id) }}</p>
                            <p>id: {{ $user->id_number }} | <i class="mdi mdi-account-multiple-plus"></i> {{ \App\Facades\Hierarchy::inviterCount($user->id) }} | <i class="mdi mdi-sitemap"></i> {{ \App\Facades\Hierarchy::teamCount($user->id) }}</p>
                        </div>
                    </div>
                </a>
                {!! \App\Facades\Hierarchy::getHierarchyTree($user->id) !!}
            </li>
        </ul>
    </div>
</div>
        </div>
    </div>

@endsection



@push('styles')
    <link rel="stylesheet" href="/user-tree-view/dist/style.css">
@endpush

@push('scripts')
    <!-- partial -->

    <script  src="/user-tree-view/dist/script.js"></script>
    <script>
        $('#child'+{{$user->id}}).addClass('active');
    </script>

@endpush
