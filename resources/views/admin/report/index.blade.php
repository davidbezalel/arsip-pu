@extends('layouts.admin_master')

@section('content')

    {{-- filtering --}}
    <section class="content font-resize">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filtering Option</h3>
                    </div>
                    <div class="box-body">
                        <form action="" id="reportfilteringform" class="form-horizontal">
                            <div class="alert redalert" id="error"></div>
                            <div class="form-group">
                                <label for="year" class="col-md-2 control-label">Tahun</label>
                                <div class="col-md-5">
                                    <select id="year" class="form-control js-example-basic-single" name="year">
                                        <option value="0" selected disabled>Silahkan pilih tahun pelaksanaan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="paket_id" class="col-md-2 control-label">Paket</label>
                                <div class="col-md-10">
                                    <select id="paketname" class="form-control js-example-basic-single" name="paket_id"
                                            disabled="true">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="paket_id" class="col-md-2 control-label">Sub - Paket</label>
                                <div class="col-md-10">
                                    <select id="subpaketname" class="form-control js-example-basic-single"
                                            name="paket_id" disabled="true">
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="content"></div>
    </section>



    {{-- data table --}}
    {{--<section class="content">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-12">--}}
    {{--<div class="box box-default">--}}
    {{--<div class="box-body">--}}
    {{--<a href="" id="add" class="general-action add"><i class="fa fa-plus-circle"></i></a>--}}
    {{--<table id="kontrak-table" class="table table-striped">--}}
    {{--<thead>--}}
    {{--<tr>--}}
    {{--<th class="no">#</th>--}}
    {{--<th>PPK</th>--}}
    {{--<th>Paket</th>--}}
    {{--<th>Date Created</th>--}}
    {{--</tr>--}}
    {{--</thead>--}}
    {{--</table>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section>--}}

    {{-- add modal --}}
    {{--<div id="kontrak-modal" class="modal fade" data-backdrop="static" role="dialog">--}}
    {{--<div class="modal-dialog" role="document">--}}
    {{--<form action="" id="kontrak-form" class="form-horizontal">--}}
    {{--<div class="modal-header">--}}
    {{--<button class="close" data-dismiss="modal" arial-lable="Close" type="button">--}}
    {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--<div class="modal-body">--}}
    {{--<div class="alert nonmodalalert redalert" id="error"></div>--}}
    {{--<div class="form-group">--}}
    {{--<label for="ppk_id" class="col-md-3 control-label">PPK</label>--}}
    {{--<div class="col-md-9">--}}
    {{--<select id="ppkname" class="form-control js-example-basic-single" name="ppk_id">--}}
    {{--</select>--}}

    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
    {{--<label for="paket_id" class="col-md-3 control-label">Paket</label>--}}
    {{--<div class="col-md-9">--}}
    {{--<select id="pakettitle" class="form-control js-example-basic-single" name="paket_id">--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal-footer">--}}
    {{--<button type="button" id="add-btn" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" class="btn pull-right btn-primary btn-flat">Add</button>--}}
    {{--<button type="button" id="update-btn" data-loading-text="<i class='fa fa-spinner fa-spin '></i>" class="btn pull-right btn-primary btn-flat">Update</button>--}}
    {{--<button id="cancel-btn" data-dismiss="modal" arial-lable="Close" class="btn pull-left btn-danger btn-flat">Cancel</button>--}}
    {{--</div>--}}
    {{--</form>--}}

    {{--</div>--}}
    {{--</div>--}}

    {{-- success modal --}}
    {{--<div id="successmodal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">--}}
    {{--<div class="modal-dialog" role="document">--}}
    {{--<div class="modal-content modalalert greenalert">--}}
    {{--<div class="modal-body">--}}
    {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
    {{--<span><i class="fa fa-check-square"></i></span><span id="successmessage"></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

@endsection