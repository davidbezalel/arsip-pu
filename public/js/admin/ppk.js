jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    var modalhide = function () {
        $('#ppk-modal').fadeOut('slow', function () {
            $(this).modal('hide');
        });
    };

    $('#add').click(function (event) {
        event.preventDefault();
        $('#ppk-form')[0].reset();
        $('#error').hide();
        $('#update-btn').hide();
        $('#add-btn').show();
        $('#ppk-modal').modal();
    });

    $('#add-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#ppk-form').serialize();
        $.ajax({
            url: '/admin/ppk/add',
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
                    $('#ppk-form')[0].reset();
                }
            }

        });
    });

    $(document).on('click', '.update', function (event) {
        event.preventDefault();
        var _data = JSON.parse($(this).attr('data-data'));
        $('input[name=ppkname]').val(_data.ppkname);
        $('input[name=companyname]').val(_data.companyname);
        $('input[name=companyleader]').val(_data.companyleader);
        $('#update-btn').attr('data-id', _data.id);

        $('#error').hide();
        $('#update-btn').show();
        $('#add-btn').hide();
        $('#ppk-modal').modal();
    });

    $('#update-btn').click(function (event) {
        event.preventDefault();
        $('#error').hide();
        $(this).button('loading');
        var _data = $('#ppk-form').serialize();
        _data += '&id=' + $(this).attr('data-id');
        $.ajax({
            url: '/admin/ppk/update',
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
                    $('#ppk-form')[0].reset();
                }
            }
        });
    });

    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        var _id = $(this).attr('data-id');
        if (confirm('Do you want to delete this PPK?')) {
            $.ajax({
                url: '/admin/ppk/delete',
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

    var table = $('#ppk-table').DataTable({
        serverSide: true,
        lengthChange: true,
        ajax: {
            url: '/admin/ppk',
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
            data: 'companyleader',
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
                return "<a href='' data-data='" + JSON.stringify(data) + "' class='action update'><i class='fa fa-pencil-square-o'></i></a>";
                    // "<a href='' data-id='" + data.id + "' class='action action-danger delete'><i class='fa fa-trash-o'></i></a>";
            }
        }],
        order: [3, 'DESC']
    });

    table.draw();
});