// save
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#btnSave').on('click', function(e) {
    e.preventDefault();

    let $btn = $(this);
    $btn.prop('disabled', true);
    $btn.find('.btn-text').addClass('hidden');
    $btn.find('.btn-loading').removeClass('hidden');
    
    $.ajax({
        url: '/category',
        method: 'POST',
        data: {
            name: $('#name').val(),
            is_expense: $('#is_expense').val()
        },
        success: function(res) {
            alert(res.message);
        },
        error: function(err) {
            alert('Gagal menyimpan kategori');
            console.log(err.responseJSON);
        },
        complete: function() {
            $btn.prop('disabled', false);
            $btn.find('.btn-text').removeClass('hidden');
            $btn.find('.btn-loading').addClass('hidden');
        }
    });
});
