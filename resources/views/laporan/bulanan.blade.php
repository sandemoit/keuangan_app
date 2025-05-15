<x-master-layout :title="$title">
    <style>
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="w-full bg-white rounded-xl shadow-sm p-3 mb-4">
        <form id="filterForm" action="{{ route('laporan.bulanan') }}" method="GET">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative w-full md:w-auto">
                    <select name="month" id="month"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ sprintf('%02d', $month) }}"
                                {{ $selectedMonth == sprintf('%02d', $month) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="relative w-full md:w-auto">
                    <select name="year" id="year"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                        @foreach (range(date('Y'), date('Y') - 5) as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- ringkasan --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
            <table class="w-full text-sm text-left text-gray-700">
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2">Saldo awal bulan</td>
                        <td class="px-4 py-2 text-right"colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right"></td>
                        <td class="px-4 py-2 text-right">{{ rupiah($saldoAwalBulanKemarin) }}</td>
                    </tr>

                    <tr class="bg-green-100 border-b">
                        <td class="px-4 py-2">Semua Pemasukan</td>
                        <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right text-green-600 font-semibold">(+)</td>
                        <td class="px-4 py-2 text-right text-green-600">{{ $pemasukan }}
                        </td>
                    </tr>

                    <tr class="bg-red-100 border-b">
                        <td class="px-4 py-2">Semua Pengeluaran</td>
                        <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right text-red-600 font-semibold">(-)</td>
                        <td class="px-4 py-2 text-right text-red-600">{{ $pengeluaran }}
                        </td>
                    </tr>

                    <tr class="bg-gray-100 font-bold border-b">
                        <td class="px-4 py-2 text-right" colspan="4">Akumulasi</td>
                        <td class="px-4 py-2 text-right {{ $akumulasi < 0 ? 'text-red-700' : '' }}">
                            {{ $akumulasi }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-2">Saldo akhir bulan</td>
                        <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right"></td>
                        <td class="px-4 py-2 text-right">
                            {{ rupiah($saldoAkhirBulanIni) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <div id="legend-chart"></div>
        </div>
    </div>

    {{-- chart --}}
    <div class="grid grid-cols-2 gap-4 my-4">
        @if (!$kategoriIncome->isEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 1.707 3.293A1 1 0 00.293 4.707l7 7a1 1 0 001.414 0L11 9.414 14.586 13H12z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Pemasukan</h5>
                    </div>
                </div>
                <!-- Donut Chart -->
                <div class="py-6" id="donut-chart-pemasukan"></div>
            </div>
        @endif

        @if (!$kategoriExpense->isEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
                <div class="flex justify-between mb-3">
                    <div class="flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-6.293 6.293a1 1 0 01-1.414-1.414l7-7a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Pengeluaran</h5>
                    </div>
                </div>
                <!-- Donut Chart -->
                <div class="py-6" id="donut-chart-pengeluaran"></div>
            </div>
        @endif
    </div>

    {{-- list kategori transaksi --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
            <table class="w-full text-sm text-left text-gray-700">
                @if (!$kategoriIncome->isEmpty())
                    <tbody>
                        @foreach ($kategoriIncome as $kategori)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $kategori->name }}</td>
                                <td class="px-4 py-2 text-right">Rp</td>
                                <td class="px-4 py-2 text-right">{{ rupiah($kategori->transactions_sum_amount) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold">
                            <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                            <td class="px-4 py-2 text-right">{{ rupiah(saldo_sum('income', date('Y-m'))) }}</td>
                        </tr>
                    </tbody>
                @else
                    <div class="px-4 py-2 bg-gray-200 text-center text-md ">
                        ~Tidak ada aktivitas dalam kategori ini~
                    </div>
                @endif
            </table>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
            <table class="w-full text-sm text-left text-gray-700">
                @if (!$kategoriExpense->isEmpty())
                    <tbody>
                        @foreach ($kategoriExpense as $kategori)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $kategori->name }}</td>
                                <td class="px-4 py-2 text-right">Rp</td>
                                <td class="px-4 py-2 text-right">{{ rupiah($kategori->transactions_sum_amount) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 font-bold">
                            <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                            <td class="px-4 py-2 text-right">{{ rupiah(saldo_sum('expense', date('Y-m'))) }}</td>
                        </tr>
                    </tbody>
                @else
                    <div class="px-4 py-2 bg-gray-200 text-center text-md ">
                        ~Tidak ada aktivitas dalam kategori ini~
                    </div>
                @endif
            </table>
        </div>
    </div>

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
        <script>
            const pemasukan = {{ $pemasukanRaw ?? 0 }};
            const pengeluaran = {{ $pengeluaranRaw ?? 0 }};
            const kategoriLabelsPemasukan = {!! json_encode($labelsIncome) !!};
            const kategoriDataPemasukan = {!! json_encode($dataIncome) !!};
            const kategoriLabelsPengeluaran = {!! json_encode($labelsExpense) !!};
            const kategoriDataPengeluaran = {!! json_encode($dataExpense) !!};
            const kategoriColorsPemasukan = {!! json_encode($colorsIncome) !!};
            const kategoriColorsPengeluaran = {!! json_encode($colorsExpense) !!};
        </script>

        <script src="{{ asset('js/chart_laporan_bulanan.js') }}"></script>
    @endpush
</x-master-layout>
