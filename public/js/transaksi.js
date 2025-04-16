$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

if (document.getElementById("transaksiTabel") && typeof simpleDatatables.DataTable !== 'undefined') { 
    const dataTable = new simpleDatatables.DataTable("#transaksiTabel", {
        searchable: true,
        paging: true,
        perPage: 10,
        perPageSelect: [10, 20, 50, 70, 100],
        sortable: true
    });
}

// Tangkap semua tombol edit
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        // Ambil accordion element
        const accordion = document.getElementById('accordion-collapse-heading-1').querySelector('button');
        
        // Buka accordion jika masih tertutup
        if (accordion.getAttribute('aria-expanded') === 'false') {
            accordion.click();
        }
        
        // Isi form dengan data transaksi
        document.getElementById('transaksi_id').value = this.getAttribute('data-id');
        document.getElementById('tipe').value = this.getAttribute('data-tipe');
        document.getElementById('tanggal').value = this.getAttribute('data-tanggal');
        document.getElementById('nominal').value = this.getAttribute('data-nominal');
        document.getElementById('kategori').value = this.getAttribute('data-kategori');
        document.getElementById('payment_method').value = this.getAttribute('data-payment');
        document.getElementById('deskripsi').value = this.getAttribute('data-deskripsi');
        document.getElementById('target_keuangan').value = this.getAttribute('data-target-keuangan');
        
        // Scroll ke form
        document.getElementById('accordion-collapse-body-1').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
        
        // Ubah teks tombol simpan untuk menunjukkan mode edit
        const btnSave = document.getElementById('btnSave');
        btnSave.textContent = 'Perbarui';
        
        // Tambahkan tombol batal (opsional)
        if (!document.getElementById('btnCancel')) {
            const btnCancel = document.createElement('button');
            btnCancel.id = 'btnCancel';
            btnCancel.type = 'button';
            btnCancel.className = 'mt-2 md:ml-2 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 md:w-auto w-full';
            btnCancel.textContent = 'Batal';
            btnCancel.addEventListener('click', resetForm);
            btnSave.parentNode.insertBefore(btnCancel, btnSave.nextSibling);
        }
    });
});

// Fungsi untuk reset form
function resetForm() {
    document.getElementById('transaksi_id').value = '';
    document.getElementById('form-transaksi').reset();
    
    // Kembalikan teks tombol
    document.getElementById('btnSave').textContent = 'Simpan';
    
    // Hapus tombol batal jika ada
    const btnCancel = document.getElementById('btnCancel');
    if (btnCancel) {
        btnCancel.remove();
    }
}

$('#form-transaksi').on('submit', function(e) {
    e.preventDefault();

    const isEdit = $('#transaksi_id').val() !== '';
    const url = isEdit ? `/transaksi/${$('#transaksi_id').val()}` : '/transaksi';
    const method = isEdit ? 'PUT' : 'POST';

    const formData = new FormData(this); // Ambil semua input termasuk file (jika ada)
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    if (isEdit) {
        formData.append('_method', 'PUT'); // Laravel hanya terima POST, override ke PUT
    }

    // Optional: Disable tombol submit sementara
    const submitButton = $(this).find('button[type="submit"]');
    submitButton.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message || 'Data berhasil disimpan');
            resetForm(); // Fungsi custom untuk reset form
            location.reload(); // Atau update tabel manual
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
            if (xhr.responseJSON?.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
            alert(errorMessage);
        },
        complete: function() {
            submitButton.prop('disabled', false).text(isEdit ? 'Update' : 'Simpan');
        }
    });
});

// Fungsi untuk memuat data transaksi berdasarkan filter tanggal
function loadTransactionsByDateRange() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Hanya kirim request jika kedua tanggal sudah diisi
    if (startDate && endDate) {
        // Tampilkan loading spinner (opsional)
        // document.getElementById('loading-spinner').classList.remove('hidden');
        
        // Perbarui URL dengan parameter query string
        const url = new URL(window.location);
        url.searchParams.set('start', startDate);
        url.searchParams.set('end', endDate);
        
        // Gunakan history.pushState untuk memperbarui URL tanpa me-refresh halaman
        window.history.pushState({}, '', url);
        
        // Lakukan fetch request ke server
        fetch(`${window.location.pathname}?start=${startDate}&end=${endDate}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Header untuk menandai request AJAX
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Untuk versi AJAX sederhana, perbarui tabel transaksi
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Ganti konten tabel dengan hasil dari server
            const tableContent = doc.querySelector('#transaction-table-content');
            if (tableContent) {
                document.querySelector('#transaction-table-content').innerHTML = tableContent.innerHTML;
            }
            
            // Sembunyikan loading spinner (opsional)
            // document.getElementById('loading-spinner').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('Error fetching data');
            // Tampilkan pesan error (opsional)
            
            // Sembunyikan loading spinner (opsional)
            // document.getElementById('loading-spinner').classList.add('hidden');
        });
    }
}

// Event listener untuk datepicker
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener untuk input tanggal awal
    const startDateInput = document.getElementById('start_date');
    if (startDateInput) {
        startDateInput.addEventListener('changeDate', function() {
            // Cek apakah tanggal akhir sudah diisi
            if (document.getElementById('end_date').value) {
                loadTransactionsByDateRange();
            }
        });
    }
    
    // Tambahkan event listener untuk input tanggal akhir
    const endDateInput = document.getElementById('end_date');
    if (endDateInput) {
        endDateInput.addEventListener('changeDate', function() {
            // Cek apakah tanggal awal sudah diisi
            if (document.getElementById('start_date').value) {
                loadTransactionsByDateRange();
            }
        });
    }
    
    // Inisialisasi nilai input dari URL jika ada
    const urlParams = new URLSearchParams(window.location.search);
    const startParam = urlParams.get('start');
    const endParam = urlParams.get('end');
    
    if (startParam) {
        startDateInput.value = startParam;
    }
    
    if (endParam) {
        endDateInput.value = endParam;
    }
});
