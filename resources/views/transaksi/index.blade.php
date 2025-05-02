@push('custom-css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet" />
@endpush
@php
    $start = request('start') ? \Carbon\Carbon::parse(request('start'))->format('d-m-Y') : '';
    $end = request('end') ? \Carbon\Carbon::parse(request('end'))->format('d-m-Y') : '';
@endphp
<x-master-layout :title="$title">
    <div class="w-full bg-white rounded-xl shadow-sm p-3 mb-4">
        <form id="filterTanggalForm" action="{{ route('transaksi') }}" method="GET">
            <div id="date-range-picker" date-rangepicker class="flex items-center mb-3">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-range-start" name="start" type="text" value="{{ $start }}"
                        datepicker datepicker-format="dd-mm-yyyy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Pilih tanggal awal">
                </div>
                <span class="mx-4 text-gray-500">ke</span>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-range-end" name="end" type="text" value="{{ $end }}"
                        datepicker datepicker-format="dd-mm-yyyy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Pilih tanggal akhir">
                </div>
                <button type="submit"
                    class="ml-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Filter</button>
            </div>
        </form>
    </div>

    <div id="accordion-collapse" data-accordion="collapse">
        <h2 id="accordion-collapse-heading-1">
            <button type="button"
                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-white text-blue-800 shadow-sm rounded-t-xl dark:border-gray-700 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 gap-3"
                data-accordion-target="#accordion-collapse-body-1" aria-expanded="false"
                aria-controls="accordion-collapse-body-1">
                <span><i class="fa-regular fa-square-plus"></i> Tambah Transaksi Disini</span>
            </button>
        </h2>
        <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
            <div class="p-5 shadow-sm bg-white">
                <form class="w-full" id="form-transaksi">
                    <input type="hidden" id="transaksi_id" name="transaksi_id" value="">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="group">
                            <label for="tipe"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                            <select id="tipe" name="tipe"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option disabled selected>Pilih tipe</option>
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                                <option value="target">Target</option>
                            </select>
                        </div>
                        <div class="group">
                            <label for="tanggal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input id="tanggal" name="tanggal" datepicker datepicker-buttons
                                    datepicker-autoselect-today value="{{ $start }}"
                                    datepicker-format="dd-mm-yyyy" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Pilih tanggal">
                            </div>
                        </div>
                        <div class="group">
                            <label for="nominal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
                            <input type="text" id="nominal" name="nominal"
                                class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                                placeholder="000" required />
                        </div>
                        <div class="group">
                            <label for="kategori"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select id="kategori" name="kategori" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Silakan pilih tipe terlebih dahulu</option>
                            </select>
                        </div>
                        <div class="group">
                            <label for="payment_method"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metode
                                Pembayaran</label>
                            <select id="payment_method" name="payment_method"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="cash" selected>Cash</option>
                                <option value="bank transfer">Bank Transfer</option>
                                <option value="e-wallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="group">
                            <label for="target_keuangan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Target Keuangan
                                (Tidak Wajib)</label>
                            <select id="target_keuangan" name="target_keuangan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option disabled selected>Pilih jika ada</option>
                                @foreach ($targetKeuangan as $target)
                                    <option value="{{ $target->id }}">{{ $target->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="group mt-4">
                        <label for="deskripsi"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <input type="text" id="deskripsi" name="deskripsi"
                            class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                            placeholder="Keterangan Transaksi" required />
                    </div>
                    <button type="submit" id="btnSave"
                        class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="w-full bg-white rounded-xl rounded-t-none shadow-sm p-5">
        <table id="transaksiTabel">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            No
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Tanggal
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Kategori
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Deskripsi
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Jumlah
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
                @foreach ($transaksi as $transaksi)
                    <tr
                        class="{{ isset($transaksi->category) ? ($transaksi->category->is_expense ? 'bg-red-200' : 'bg-green-200') : 'bg-slate-200' }}">
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ tanggal_hari($transaksi->date_trx) }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if (isset($transaksi->category))
                                {{ $transaksi->category->name }}
                            @elseif(isset($transaksi->target))
                                {{ $transaksi->target->name }}
                            @else
                                Deleted
                            @endif
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $transaksi->description }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ rupiah($transaksi->amount) }}
                        </td>
                        <td>
                            <div class="flex gap-4">
                                <button href="#" class="btn-edit text-blue-600 hover:text-blue-900"
                                    data-id="{{ $transaksi->id }}" data-tanggal="{{ $transaksi->date_trx }}"
                                    data-tipe="{{ isset($transaksi->category) && $transaksi->category->is_expense ? 'expense' : 'income' }}"
                                    data-kategori="{{ isset($transaksi->category) ? $transaksi->category->id : null }}"
                                    data-nominal="{{ $transaksi->amount }}"
                                    data-payment="{{ $transaksi->payment_method ?? 'cash' }}"
                                    data-deskripsi="{{ $transaksi->description }}"
                                    data-target-keuangan="{{ $transaksi->description ?? null }}"
                                    class="btn-edit text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button data-modal-target="modal-delete" data-modal-toggle="modal-delete"
                                    data-delete-url="{{ route('transaksi.destroy', $transaksi->id) }}"
                                    class="btn-delete text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('components.modal-delete')

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <script src="{{ asset('js/transaksi.js') }}"></script>
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
    @endpush
</x-master-layout>
