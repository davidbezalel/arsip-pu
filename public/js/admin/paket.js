jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    var selectedyear = 0;

    var drawtable = function () {
        $('#paket-table').data('year', {year: selectedyear});
    }

    var modalhide = function () {
        $('#paket-modal').fadeOut('slow', function () {
            $(this).modal('hide');
        });
    };

    $('#add').click(function (event) {
        event.preventDefault();
        $('#yearsofwork').hide();
        $('#paket-form')[0].reset();
        $('#paketmodalbody').empty();
        $('#error').hide();
        $('#update-btn').hide();
        $('#add-btn').show();
        $('#addsubpaket').show();
        $('#paket-modal').modal();
    });

    $('#add-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#paket-form').serialize();
        $.ajax({
            url: '/admin/paket/add',
            type: 'POST',
            data: _data,
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                $('#add-btn').button('reset');
                if (!data.status) {
                    $('#error').empty().append(data.message).show();
                } else {
                    modalhide();
                    successnotification(data.message);
                    table.draw();
                    $('#paket-form')[0].reset();
                    $('#paketmodalbody').empty();
                }
            }

        });
    });

    $(document).on('click', '.update', function (event) {
        event.preventDefault();
        $('#paketmodalbody').empty();
        var _data = JSON.parse($(this).attr('data-data'));
        var _subpaket = JSON.parse($(this).attr('data-subpaket'));
        $('input[name=title]').val(_data.title);
        $('input[name=startyear]').val(_data.startyear);
        if (_data.ismultiyears) {
            $('input[name=ismultiyears]').attr('checked', true);
            $('input[name=yearsofwork]').val(_data.yearsofwork).attr('disabled', true).show();
        } else {
            $('input[name=yearsofwork]').hide();
            $('input[name=ismultiyears]').attr('checked', false);
        }
        $('#update-btn').attr('data-id', _data.id);
        $('#addsubpaket').hide();



        $.each(_subpaket, function (index, value) {
            if (value.title != _data.title) {
                var _formgroup = '<div class="form-group">' +
                    '                        <label for="ppk_id" class="col-md-3 control-label">Sub-Paket Title</label>' +
                    '                        <div class="col-md-9">' +
                    '                            <input disabled type="text" value="' + value.title + '" name="subpakettitle[]" class="form-control" placeholder="eg. Pengembangan Jalan">' +
                    '                        </div>' +
                    '                    </div>';
                $('#paketmodalbody').append(_formgroup);
            }
        });

        $('#error').hide();
        $('#update-btn').show();
        $('#add-btn').hide();
        $('#paket-modal').modal();
    });

    $('#update-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#paket-form').serialize();
        _data += '&id=' + $(this).attr('data-id');
        $.ajax({
            url: '/admin/paket/update',
            type: 'POST',
            data: _data,
            headers: {'X-CSRF-TOKEN': token},
            success: function (data) {
                $('#update-btn').button('reset');
                if (!data.status) {
                    $('#error').empty().append(data.message).show();
                } else {
                    modalhide();
                    successnotification(data.message);
                    table.draw();
                    $('#paket-form')[0].reset();
                    $('#paketmodalbody').empty();
                }
            }
        });
    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        var _id = $(this).attr('data-id');
        if (confirm('Do you want to delete this Paket?')) {
            $.ajax({
                url: '/admin/paket/delete',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': token},
                data: {'id': _id},
                cache: false,
                success: function (data) {
                    if (data.status) {
                        successnotification(data.message);
                        table.draw();
                    }
                }
            });
        }
    });

    $('#addsubpaket').click(function (event) {
        event.preventDefault();
        var _formgroup = '<div class="form-group">' +
            '                        <label for="ppk_id" class="col-md-3 control-label">Sub-Paket Title</label>' +
            '                        <div class="col-md-9">' +
            '                            <input type="text" name="subpakettitle[]" class="form-control" placeholder="eg. Pengembangan Jalan">' +
            '                        </div>' +
            '                    </div>';
        $('#paketmodalbody').append(_formgroup);

    });

    $('input[name=ismultiyears]').click(function () {
        if ($('input[name=ismultiyears]:checked').length > 0) {
            $('#yearsofwork').show();
        } else {
            $('#yearsofwork').hide();
        }
    });

    var table = $('#paket-table').DataTable({
        serverSide: true,
        lengthChange: true,
        ajax: {
            url: '/admin/paket',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            data: function (d) {
                $.extend(d, this.data);
                var _year = $('#paket-table').data('year');
                if (_year) {
                    $.extend(d, _year);
                }
            }
        },
        columns: [{
            data: 'no',
            searchable: false,
            orderable: false,
            className: 'no'
        }, {
            data: 'title'
        }, {
            data: 'startyear'
        }, {
            data: 'endyear',
        }, {
            data: null,
            orderable: false,
            className: 'right',
            render: function (data) {
                return "<a href='' data-data='" + JSON.stringify(data) + "' data-subpaket='" + JSON.stringify(data.subpaket) + "' class='action update'><i class='fa fa-pencil-square-o'></i></a>";
                // "<a href='' data-id='" + data.id + "' class='action action-danger delete'><i class='fa fa-trash-o'></i></a>";
            }
        }],
        order: [3, 'DESC']
    });

    drawtable();
    table.draw();

    $('.dataTables_wrapper').prepend('<div class="row dataTables_filter">' +
        '   <div class="col-md-6">' +
        '       <select id="year" class="form-control js-example-basic-single" name="year">' +
        '           <option value="0" selected>All Years</option>' +
        '       </select>' +
        '   </div>' +
        '</div>');

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

    $(document).on('change', '#year', function() {
        selectedyear = $(this).val();
        drawtable();
        table.draw();
    });
});