jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    var modalhide = function () {
        $('#kontrak-modal').fadeOut('slow', function () {
            $(this).modal('hide');
        });
    };

    $.ajax({
        url: '/admin/ppk/get',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        success: function (data) {
            if (data.status) {
                var _data = data.data;
                var _option = '';
                $.each(_data, function (index, value) {
                    _option = '<option value="' + value.id + '"> ' + value.name + '</option>';
                    $('#ppkname').append(_option);
                });
            }
        }
    });

    $.ajax({
        url: '/admin/paket/get',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        success: function (data) {
            if (data.status) {
                var _data = data.data;
                var _option = '';
                $.each(_data, function (index, value) {
                    _option = '<option value="' + value.id + '"> ' + value.title + '</option>';
                    $('#pakettitle').append(_option);
                });
            }
        }
    });

    $('#add').click(function (event) {
        event.preventDefault();
        $('#kontrak-form')[0].reset();
        $('#error').hide();
        $('#update-btn').hide();
        $('#add-btn').show();
        $('#kontrak-modal').modal();
    });

    $('#add-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#kontrak-form').serialize();
        $.ajax({
            url: '/admin/kontrak/add',
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
                    $('#kontrak-form')[0].reset();
                }
            }

        });
    });

    $(document).on('click', '.update', function (event) {
        event.preventDefault();
        var _data = JSON.parse($(this).attr('data-data'));
        $('input[name=title]').val(_data.title);
        $('input[name=year]').val(_data.year);
        $('#update-btn').attr('data-id', _data.id);

        $('#error').hide();
        $('#update-btn').show();
        $('#add-btn').hide();
        $('#kontrak-modal').modal();
    });

    $('#update-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#kontrak-form').serialize();
        _data += '&id=' + $(this).attr('data-id');
        $.ajax({
            url: '/admin/kontrak/update',
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
                    $('#kontrak-form')[0].reset();
                }
            }
        });
    });

    $(".js-example-basic-single").select2();

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        var _id = $(this).attr('data-id');
        if (confirm('Do you want to delete this Paket?')) {
            $.ajax({
                url: '/admin/kontrak/delete',
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

    var table = $('#kontrak-table').DataTable({
        serverSide: true,
        lengthChange: true,
        ajax: {
            url: '/admin/ppkappointment',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token}
        },
        columns: [{
            data: 'no',
            searchable: false,
            orderable: false,
            className: 'no'
        }, {
            data: 'ppkname'
        }, {
            data: 'title'
        }, {
            data: 'created_at',
            orderable: false,
            searchable: false,
            className: 'right'
        }],
        order: [3, 'DESC']
    });
});