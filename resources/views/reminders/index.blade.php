@push('custom-css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet" />
@endpush
<x-master-layout :title="$title">

    <div id="accordion-collapse" data-accordion="collapse">
        <h2 id="accordion-collapse-heading-1">
            <button type="button"
                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-white text-blue-800 shadow-sm rounded-t-xl dark:border-gray-700 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 gap-3"
                data-accordion-target="#accordion-collapse-body-1" aria-expanded="false"
                aria-controls="accordion-collapse-body-1">
                <span><i class="fa-regular fa-calendar-check"></i> Buat Reminder Keuangan</span>
            </button>
        </h2>
        <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
            <div class="p-5 border border-t-0 rounded-b-xl border-gray-200 dark:border-gray-700 bg-white">
                <form class="w-full" method="POST" action="{{ route('reminder-keuangan.store') }}">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Nama Pengeluaran -->
                        <div class="group">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nama Pengeluaran
                            </label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Bayar Kontrakan" required />
                        </div>

                        <!-- Tanggal Bulanan -->
                        <div class="group">
                            <label for="day_of_month"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Tanggal Pengingat (1-31)
                            </label>
                            <input type="number" name="day_of_month" id="day_of_month" min="1" max="31"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="2" required />
                        </div>

                        <!-- Nominal -->
                        <div class="group">
                            <label for="nominal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Nominal
                            </label>
                            <input type="number" name="nominal" id="nominal" min="1"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="500000" required />
                        </div>

                        <!-- Deskripsi (opsional) -->
                        <div class="group">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi
                                (Opsional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Contoh: Transfer ke pemilik kontrakan..."></textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <button type="submit" id="btnSave"
                        class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <span class="btn-text">Simpan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="w-full bg-white rounded-xl rounded-t-none shadow-sm p-5">
        <table id="remindersTabel">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            Nama
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Tiap Tanggal
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Nominal
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Description
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Status
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Aksi
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reminders as $reminders)
                    <tr>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $reminders->name }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $reminders->day_of_month }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ rupiah($reminders->nominal) }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $reminders->description ?? '-' }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $reminders->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </td>
                        <td>
                            <button data-modal-target="modal-delete" data-modal-toggle="modal-delete"
                                data-delete-url="{{ route('reminder-keuangan.destroy', $reminders->id) }}"
                                class="btn-delete text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('components.modal-delete')

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <script>
            if (document.getElementById("remindersTabel") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#remindersTabel", {
                    searchable: true,
                    sortable: false
                });
            }
        </script>
        <script>
            // Ambil semua tombol delete
            const deleteButtons = document.querySelectorAll('.btn-delete');

            // Tambahkan event listener untuk setiap tombol
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil URL delete dari atribut data
                    const deleteUrl = this.getAttribute('data-delete-url');

                    // Set URL form delete
                    const deleteForm = document.getElementById('delete-form');
                    deleteForm.action = deleteUrl;
                });
            });
        </script>
        <script src="{{ asset('js/reminders.js') }}"></script>
    @endpush
</x-master-layout>
