$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#btnSave').on('click', function(e) {
    e.preventDefault();

    const name = $('#name').val();
    const is_expense = $('#is_expense').is(':checked') ? 1 : 0; // Fix checkbox value

    $.ajax({
        url: '/category',
        method: 'POST',
        data: {
            name,
            is_expense
        },
        success: function(res) {
            alert(res.message);
            $('#name').val('');
            $('#is_expense').prop('checked', false);
            location.reload();            
        },
        error: function(err) {
            alert('Gagal menyimpan kategori');
            console.log(err.responseJSON);
        }
    });
});
