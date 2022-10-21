@extends('layouts.admin')

@section('in_content')

    <div class="page-wrapper" style="background: #f2f7f8;">

        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Изменить курс валют</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <form action="/settings-update/{{ $settings_item->id }}" method="POST" class="form-horizontal form-material" >
                                {{ csrf_field() }}
                                @method("PUT")
                                <div class="form-group">
                                    <label class="col-md-12">{{ $settings_item->title }}</label>
                                    <div class="col-md-12">
                                        <input type="number" value="{!! $settings_item->value !!}" name="value" class="form-control form-control-line">
                                        @if ($errors->has('value'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('value') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success" type="submit">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection



