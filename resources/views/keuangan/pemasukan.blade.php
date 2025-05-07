@push('custom-css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet" />
@endpush
@php
    $start = request('start') ? \Carbon\Carbon::parse(request('start'))->format('d-m-Y') : '';
    $end = request('end') ? \Carbon\Carbon::parse(request('end'))->format('d-m-Y') : '';
@endphp

<x-master-layout :title="$title">
    <div class="w-full bg-white rounded-xl shadow-sm p-3 mb-4">
        <form id="filterTanggalForm" action="{{ route('keuangan.masuk') }}" method="GET">
            <div id="date-range-picker" date-rangepicker class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-auto">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-range-start" name="start" type="text" value="{{ $start }}"
                        datepicker datepicker-format="dd-mm-yyyy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                        placeholder="Pilih tanggal awal">
                </div>
                <span class="text-gray-500">ke</span>
                <div class="relative w-full sm:w-auto">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input id="datepicker-range-end" name="end" type="text" value="{{ $end }}"
                        datepicker datepicker-format="dd-mm-yyyy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                        placeholder="Pilih tanggal akhir">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Filter</button>
            </div>
        </form>
    </div>

    <div class="w-full bg-white rounded-xl shadow-sm p-5">
        <table id="keuanganMasuk">
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
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $transaksi)
                    <tr class="bg-green-200">
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ tanggal_hari($transaksi->date_trx) }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $transaksi->category->name }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $transaksi->description }}
                        </td>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ rupiah($transaksi->amount) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <script>
            if (document.getElementById("keuanganMasuk") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#keuanganMasuk", {
                    searchable: true,
                    paging: true,
                    perPage: 10,
                    perPageSelect: [10, 20, 50, 70, 100],
                    sortable: true
                });
            }
        </script>
    @endpush
</x-master-layout>
