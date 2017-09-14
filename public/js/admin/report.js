jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    $('#content').hide();

    $.ajax({
        url: '/admin/paket/year/get',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        success: function (data) {
            if (data.status) {
                var _data = data.data;
                for (i = _data.startyear; i <= _data.endyear; i++) {
                    $('#year').append("<option value='" + i + "'>" + i + "</option>");
                }
            }
        }
    });

    var makemainreport = function (paketid) {
        $.ajax({
            url: '/admin/report/main/get/' + paketid,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var _data = data.data;
                    var _html = '<div class="row">' +
                        '            <div class="col-md-12">' +
                        '                <div class="box box-default">' +
                        '                    <div class="box-header with-border">' +
                        '                        <h3 class="box-title">' + "Laporan Utama" + '</h3>' +
                        '                        <div class="box-tools pull-right">' +
                        '                           <button type="button" class="btn btn-box-tool" data-widget="collapse">' +
                        '                               <i class="fa fa-minus"></i>' +
                        '                            </button>' +
                        '                       </div>' +
                        '                    </div>' +
                        '                    <div class="box-body">' +
                        '                       <table class="table table-bordered">' +
                        '                           <tbody id="utama">' +
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
                    $('#content').empty().append(_html);
                    $.each(_data, function (index, value) {
                        if (value.isfilesubmitted == 0) {
                            _action = '<button id="serahkan" data-report="' + value.id + '" class="btn btn-danger btn-flat">Berkas Belum diserahkan</button >';
                        } else if (value.isfilesubmitted == 1 && value.isavailable == 1) {
                            _action = '<button id="pinjam" data-report="' + value.id + '" data-document-report="' + value.filesubmissionid + '" class="btn btn-success btn-flat">Berkas Lengkap</button >';
                        } else if (value.isfilesubmitted == 1 && value.isavailable == 0) {
                            _action = '<button id="kembalikan" data-report="' + value.id + '" data-peminjaman-berkas="' + value.loanfileid + '" class="btn btn-primary btn-flat">Berkas dipinjam</button >';
                        }
                        _tr = '<tr>' +
                            '       <td>' + value.reportparamtitle + '</td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.isfilesubmitted == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.isavailable == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;">' + _action + '</td>' +
                            '</tr>';
                        $('#utama').append(_tr);
                    });
                    $('#content').show();
                }
            }
        });
    };

    var makemcreport = function (paketid) {
        $.ajax({
            url: '/admin/report/mc/get/' + paketid,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var _data = data.data;
                    var _title = _data[0].title;
                    var _html = '<div class="row">' +
                        '            <div class="col-md-12">' +
                        '                <div class="box box-default">' +
                        '                    <div class="box-header with-border">' +
                        '                        <h3 class="box-title">' + _data[0].title + '</h3>' +
                        '                        <div class="box-tools pull-right">' +
                        '                           <button type="button" class="btn btn-box-tool" data-widget="collapse">' +
                        '                               <i class="fa fa-minus"></i>' +
                        '                            </button>' +
                        '                       </div>' +
                        '                    </div>' +
                        '                    <div class="box-body">' +
                        '                       <table class="table table-bordered">' +
                        '                           <tbody id="' + _data[0].title + '">' +
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
                    $('#content').empty().append(_html);
                    $.each(_data, function (index, value) {
                        console.log(value.title, _title);
                        if (value.title != _title) {
                            var _html = '<div class="row">' +
                                '            <div class="col-md-12">' +
                                '                <div class="box box-default">' +
                                '                    <div class="box-header with-border">' +
                                '                        <h3 class="box-title">' + value.title + '</h3>' +
                                '                        <div class="box-tools pull-right">' +
                                '                           <button type="button" class="btn btn-box-tool" data-widget="collapse">' +
                                '                               <i class="fa fa-minus"></i>' +
                                '                            </button>' +
                                '                       </div>' +
                                '                    </div>' +
                                '                    <div class="box-body">' +
                                '                       <table class="table table-bordered">' +
                                '                           <tbody id="' + value.title + '">' +
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
                            _title = value.title;
                        }
                    });

                    $.each(_data, function (index, value) {
                        if (value.isfilesubmitted == 0) {
                            _action = '<button id="serahkan" data-report="' + value.id + '" class="btn btn-danger btn-flat">Berkas Belum diserahkan</button >';
                        } else if (value.isfilesubmitted == 1 && value.isavailable == 1) {
                            _action = '<button id="pinjam" data-report="' + value.id + '" data-document-report="' + value.filesubmissionid + '" class="btn btn-success btn-flat">Berkas Lengkap</button >';
                        } else if (value.isfilesubmitted == 1 && value.isavailable == 0) {
                            _action = '<button id="kembalikan" data-report="' + value.id + '" data-peminjaman-berkas="' + value.loanfileid + '" class="btn btn-primary btn-flat">Berkas dipinjam</button >';
                        }
                        _tr = '<tr>' +
                            '       <td>' + value.reportparamtitle + '</td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.isfilesubmitted == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;"><i class="fa ' + (value.isavailable == 0 ? "fa-remove" : "fa-check") + '"></i></td>' +
                            '       <td style="text-align: right;">' + _action + '</td>' +
                            '</tr>';
                        $('#' + value.title).append(_tr);
                    });
                    $('#content').show();
                }
            }
        });
    };


    $('#paketname').select2();
    $('#subpaketname').select2();

    $('#error').hide();

    $('#year').change(function () {
        var _year = $(this).val();
        $('#content').hide();

        $.ajax({
            url: '/admin/paket/get/year/' + _year,
            type: 'POST',
            cache: false,
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var _data = data.data;
                    var _option = '';
                    $('#paketname').empty().append('<option value="0" selected disabled>Silahkan pilih Paket.</option>');
                    $.each(_data, function (index, value) {
                        _option = '<option value="' + value.id + '"> ' + value.title + '</option>';
                        $('#paketname').append(_option);
                        $('#paketname').attr('disabled', false);
                    });
                    $('#subpaketname').empty().attr('disabled', true);
                }
            }
        });
    });


    $('#paketname').change(function () {
        var _id = $(this).val();
        var _isall = false;
        $.ajax({
            url: '/admin/paket/get/subpaket/' + _id,
            type: 'POST',
            cache: false,
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                if (data.status) {
                    var _data = data.data;
                    var _option = '';
                    if (_data.length > 1) {
                        $('#subpaketname').empty().append('<option value="0" selected>Tampilkan Laporan Utama.</option>');
                        $.each(_data, function (index, value) {
                            if (value.reporttype_id == 2) {
                                _option = '<option value="' + value.id + '"> ' + value.title + '</option>';
                                $('#subpaketname').append(_option);
                            }
                        });
                        $('#subpaketname').attr('disabled', false);
                        makemainreport(_id);
                    } else {
                        $('#subpaketname').empty().append('<option value="0" selected disabled>Paket ini tidak memiliki Sub-Paket.</option>');
                        $('#subpaketname').attr('disabled', true);
                    }
                }
            }
        });
    });

    $('#subpaketname').change(function () {
        if (($('#subpaketname').val() == null || $('#subpaketname').val() == 0) && $('#paketname').val() != null) {
            makemainreport($('#paketname').val());
        } else if ($('#subpaketname').val() != null && $('#paketname').val() != null) {
            makemcreport($('#subpaketname').val());
        }
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
                        if (($('#subpaketname').val() == null || $('#subpaketname').val() == 0) && $('#paketname').val() != null) {
                            makemainreport($('#paketname').val());
                        } else if ($('#subpaketname').val() != null && $('#paketname').val() != null) {
                            makemcreport($('#subpaketname').val());
                        }
                    }
                }
            });
        }
    });

    $(document).on('click', '#pinjam', function (event) {
        if (confirm('Apakah anda yakin berkas yang dipinjam telah lengkap?')) {
            var _data = 'report_id=' + $(this).attr('data-report') + '&filesubmissionid=' + $(this).attr('data-document-report');
            $.ajax({
                url: '/admin/laporan/pinjam',
                type: 'POST',
                data: _data,
                headers: {'X-CSRF-TOKEN': token},
                success: function (data) {
                    if (data.status) {
                        if (($('#subpaketname').val() == null || $('#subpaketname').val() == 0) && $('#paketname').val() != null) {
                            makemainreport($('#paketname').val());
                        } else if ($('#subpaketname').val() != null && $('#paketname').val() != null) {
                            makemcreport($('#subpaketname').val());
                        }
                    }
                }
            });
        }
    });

    $(document).on('click', '#kembalikan', function (event) {
        if (confirm('Apakah anda yakin berkas yang dikembaliakan telah lengkap?')) {
            var _data = 'report_id=' + $(this).attr('data-report') + '&loanfileid=' + $(this).attr('data-peminjaman-berkas');
            $.ajax({
                url: '/admin/laporan/kembalikan',
                type: 'POST',
                data: _data,
                headers: {'X-CSRF-TOKEN': token},
                success: function (data) {
                    if (data.status) {
                        if (($('#subpaketname').val() == null || $('#subpaketname').val() == 0) && $('#paketname').val() != null) {
                            makemainreport($('#paketname').val());
                        } else if ($('#subpaketname').val() != null && $('#paketname').val() != null) {
                            makemcreport($('#subpaketname').val());
                        }
                    }
                }
            });
        }
    });
})
;