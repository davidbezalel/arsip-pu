jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    $('#content').hide();

    $.ajax({
        url: '/admin/ppk/get',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        success: function (data) {
            if (data.status) {
                var _data = data.data;
                var _option = '';
                $.each(_data, function (index, value) {
                    _option = '<option value="' + value.id + '"> ' + value.ppkname + '</option>';
                    $('#ppkname').append(_option);
                });
            }
        }
    });

    $('#ppkname').select2();
    $('#paketname').select2();

    $('#error').hide();

    $('#ppkname').change(function () {
        var _id = $(this).val();
        $('#content').hide();

        $.ajax({
            url: '/admin/paket/get/ppk/' + _id,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var _data = data.data;
                    var _option = '';
                    if (_data.length > 0) {
                        $('#paketname').empty().append('<option value="0" selected disabled>Silahkan pilih Paket.</option>');
                        $.each(_data, function (index, value) {
                            _option = '<option value="' + value.paket_id + '"> ' + value.paket.title + '</option>';
                            $('#paketname').append(_option);
                            $('#paketname').attr('disabled', false);
                        });
                    } else {
                        $('#paketname').empty().append('<option value="0" selected disabled>PPK tidak memiliki Paket.</option>');
                    }
                }
            }
        });
    });

    var makereport2 = function () {
        var _ppkid = $('#ppkname').val();
        var _paketid = $('#paketname').val();
        var _data = 'ppk_id=' + _ppkid + '&paket_id=' + _paketid;
        $.ajax({
            url: '/admin/laporan/get',
            type: 'POST',
            data: _data,
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var __data = data.data;
                    var _tr = '';
                    var _action = '';
                    $.each(__data, function (index, value) {
                        if (value.is_reported == 0) {
                            _action = '<button id="serahkan" data-report="' + value.id + '" class="btn btn-primary btn-flat">Serahkan Berkas</button >';
                        } else if (value.is_reported == 1 && value.is_available == 1) {
                            _action = '<button id="pinjam" data-report="' + value.id + '" data-document-report="' + value.document_report_id + '" class="btn btn-success btn-flat">Pinjam Berkas</button >';
                        } else if (value.is_reported == 1 && value.is_available == 0) {
                            _action = '<button id="kembalikan" data-report="' + value.id + '" date-peminjaman-berkas="' + value.peminjaman_berkas_id + '" class="btn btn-danger btn-flat">Kembalikan Berkas</button >';
                        }
                        _tr = '<tr>' +
                            '       <td>' + value.report_param_name + '</td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.is_reported == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.is_available == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;">' + _action + '</td>' +
                            '</tr>';
                        $('#' + value.report_classification_name).append(_tr);
                    });
                    $('#content').show();
                }
            }
        });
    };

    var makereport = function () {
        $.ajax({
            url: '/admin/reportclassification/get',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var __data = data.data;
                    $('#content').empty();
                    $.each(__data, function (index, value) {
                        var _html = '<div class="row">' +
                            '            <div class="col-md-12">' +
                            '                <div class="box box-default">' +
                            '                    <div class="box-header with-border">' +
                            '                        <h3 class="box-title">' + value.name + '</h3>' +
                            '                        <div class="box-tools pull-right">' +
                            '                           <button type="button" class="btn btn-box-tool" data-widget="collapse">' +
                            '                               <i class="fa fa-minus"></i>' +
                            '                            </button>' +
                            '                       </div>' +
                            '                    </div>' +
                            '                    <div class="box-body">' +
                            '                       <table class="table table-bordered">' +
                            '                           <tbody id="' + value.name + '">' +
                            '                               <tr>' +
                            '                                   <th>Nama Laporan</th>' +
                            '                                   <th>Status Penyerahan</th>' +
                            '                                   <th>Status Ketersediaan</th>' +
                            '                                   <th>Action</th>' +
                            '                               </tr>' +
                            '                           </tbody>' +
                            '                       </table>' +
                            '                    </div>' +
                            '                </div>' +
                            '            </div>' +
                            '        </div>';
                        $('#content').append(_html);
                    });
                }
            }
        })
    };

    $('#paketname').change(function () {
        makereport();
        makereport2();
    });


    $(document).on('click', '#serahkan', function (event) {
        if (confirm('Apakah anda yakin berkas yang diserahkan telah lengkap?')) {
            var _data = 'report_id=' + $(this).attr('data-report');
            $.ajax({
                url: '/admin/laporan/report',
                type: 'POST',
                data: _data,
                headers: {'X-CSRF-TOKEN': token},
                success: function (data) {
                    if (data.status) {
                        makereport();
                        makereport2();
                    }
                }
            });
        }
    });

    $(document).on('click', '#pinjam', function (event) {
        if (confirm('Apakah anda yakin berkas yang dipinjam telah lengkap?')) {
            var _data = 'report_id=' + $(this).attr('data-report') +'&document_report_id=' + $(this).attr('data-document-report');
            $.ajax({
                url: '/admin/laporan/pinjam',
                type: 'POST',
                data: _data,
                headers: {'X-CSRF-TOKEN': token},
                success: function (data) {
                    if (data.status) {
                        makereport();
                        makereport2();
                    }
                }
            });
        }
    });

    $(document).on('click', '#kembalikan', function (event) {
        if (confirm('Apakah anda yakin berkas yang dikembaliakan telah lengkap?')) {
            var _data = 'report_id=' + $(this).attr('data-report') +'&peminjaman_berkas_id=' + $(this).attr('data-peminjaman-berkas');
            $.ajax({
                url: '/admin/laporan/kembalikan',
                type: 'POST',
                data: _data,
                headers: {'X-CSRF-TOKEN': token},
                success: function (data) {
                    if (data.status) {
                        makereport();
                        makereport2();
                    }
                }
            });
        }
    });
});