jQuery(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr('content');

    $('#logout').click(function (event) {
       event.preventDefault();
        $.ajax({
            url: '/admin/logout',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            cache: false,
            success: function (data) {
                if (!data.status) {
                    console.log(data.message);
                } else {
                    window.location.replace('/admin/login');
                }
            }
        });
    });
});