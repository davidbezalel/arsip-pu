jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');
    var selectedyear = 0;

    var successnotification = function (message) {
        $('#successmessage').empty().append(message);
        $('#successmodal').modal();
    };

    var modalhide = function () {
        $('#ppk-modal').fadeOut('slow', function () {
            $(this).modal('hide');
        });
    };

    var drawtable = function () {
        $('#ppk-table').data('year', {year: selectedyear});
        console.log(selectedyear);
    }

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
        $('input[name=ppkid]').val(_data.ppkid);
        $('input[name=name]').val(_data.name);
        $('input[name=yearupdate]').val(_data.year);
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

    drawtable();
    var table = $('#ppk-table').DataTable({
        serverSide: true,
        lengthChange: true,
        ajax: {
            url: '/admin/ppk',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            data: function (d) {
                $.extend(d, this.data);
                var _year = $('#ppk-table').data('year');
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
            data: 'ppkid'
        }, {
            data: 'name',
        }, {
            data: 'year',
            orderable: false,
            searchable: false,
            className: 'right'
        }, {
            data: null,
            orderable: false,
            className: 'right',
            render: function (data) {
                return "<a href='' data-data='" + JSON.stringify(data) + "' class='action update'><i class='fa fa-pencil-square-o'></i></a>";            }
        }],
        order: [4, 'DESC']
    });

    $('.dataTables_wrapper').prepend('<div class="row dataTables_filter">' +
        '   <div class="col-md-6">' +
        '       <select id="year" class="form-control js-example-basic-single" name="ppk_id">' +
        '           <option value="0" selected>All Years</option>' +
        '       </select>' +
        '   </div>' +
        '</div>');

    $.ajax({
        url: '/admin/ppk/year/get',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        success: function (data) {
            if (data.status) {
                var _data = data.data;
                $.each(_data, function (index, value) {
                    $('#year').append("<option value='" + index + "'>" + index + "</option>");
                });
            }
        }
    });

    $(document).on('change', '#year', function() {
        selectedyear = $(this).val();
        drawtable();
        table.draw();
    });



});