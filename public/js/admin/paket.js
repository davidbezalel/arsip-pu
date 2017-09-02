jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    var modalhide = function () {
        $('#paket-modal').fadeOut('slow', function () {
            $(this).modal('hide');
        });
    };

    $('#add').click(function (event) {
        event.preventDefault();
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
        $('input[name=year]').val(_data.year);
        $('input[name=companyprovider]').val(_data.companyprovider);
        $('#update-btn').attr('data-id', _data.id);
        $('#addsubpaket').hide();



        $.each(_subpaket, function (index, value) {
            var _formgroup = '<div class="form-group">' +
                '                        <label for="ppk_id" class="col-md-3 control-label">Sub-Paket Title</label>' +
                '                        <div class="col-md-9">' +
                '                            <input disabled type="text" value="' + value.title + '" name="subpakettitle[]" class="form-control" placeholder="eg. Pengembangan Jalan">' +
                '                        </div>' +
                '                    </div>';
            $('#paketmodalbody').append(_formgroup);
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

    var table = $('#paket-table').DataTable({
        serverSide: true,
        lengthChange: true,
        ajax: {
            url: '/admin/paket',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token}
        },
        columns: [{
            data: 'no',
            searchable: false,
            orderable: false,
            className: 'no'
        }, {
            data: 'title'
        }, {
            data: 'year'
        }, {
            data: 'created_at',
            orderable: false,
            searchable: false,
            className: 'right'
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

    table.draw();
});