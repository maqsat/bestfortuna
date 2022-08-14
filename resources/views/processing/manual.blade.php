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
                    <h3 class="text-themecolor m-b-0 m-t-0">Тип оплата  - "Скан квитанции", Сумма оплаты - {{ $cost }} {{ $currency_symbol }}</h3>
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
                            <form action="/pay-processing/{{ $order->id }}"  method="post"   enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="manual">
                                <h4 class="card-title"> Квитанция об оплате</h4>
                                <label for="input-file-now">Загрузить изображение или перетащите в эту область</label>
                                <input type="file" id="input-file-now" class="dropify" name="scan" />
                                <button type="submit" class="btn btn-success waves-effect waves-light m-t-20">Отправить на проверку</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Реквизиты и счета</h4>
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-warning"> KASPI</div>
                        <p class="ribbon-content"> ТОО «Best Fortune»</p>
                        <p class="ribbon-content"> БИН </p>
                        <p class="ribbon-content"> Номер перевода </p>
                        <p class="ribbon-content"> Республика Казахстан, города Нур-Султан, ул. Кубрина 20/1 ВП6</p>
                    </div>
                </div>
                {{--<div class="col-md-6">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-danger">Расчетный счет в АО «Альфа-Банк»</div>
                        <p class="ribbon-content"> БИК: ALFAKZKA</p>
                        <p class="ribbon-content"> Счет ТГ: KZ369470398993479303</p>
                    </div>
                </div>--}}
                {{--<div class="col-md-6">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-danger"> Расчетный счет в «ForteBank»</div>
                        <p class="ribbon-content"> БИК: IRTYKZKA</p>
                        <p class="ribbon-content"> Счет ТГ: </p>
                        <p class="ribbon-content"> Счет USD: </p>
                    </div>
                </div>--}}
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
<link rel="stylesheet" href="/monster_admin/assets/plugins/dropify/dist/css/dropify.min.css">
@endpush

@push('scripts')
<!-- jQuery file upload -->
<script src="/monster_admin/assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
@endpush
