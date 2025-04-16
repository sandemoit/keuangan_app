<x-master-layout :title="$title">
    <style>
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- ringkasan --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
            <table class="w-full text-sm text-left text-gray-700">
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2">Saldo awal hari</td>
                        <td class="px-4 py-2 text-right"colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right"></td>
                        <td class="px-4 py-2 text-right">{{ rupiah($saldoAwalHariKemarin) }}</td>
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
                        <td class="px-4 py-2 text-right {{ $akumulasi < 0 ? 'text-red-700' : '' }}">{{ $akumulasi }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-2">Saldo akhir hari</td>
                        <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right"></td>
                        <td class="px-4 py-2 text-right">
                            {{ $saldoAkhirHariIni }}
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
                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-6.293 6.293a1 1 0 01-1.414-1.414l7-7a1 1 0 011.414 0L11 10.586 14.586 7H12z"
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
                                d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 1.707 3.293A1 1 0 00.293 4.707l7 7a1 1 0 001.414 0L11 9.414 14.586 13H12z"
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
                            <td class="px-4 py-2 text-right">{{ rupiah(saldo_sum('income', date('Y-m-d'))) }}</td>
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
                            <td class="px-4 py-2 text-right">{{ rupiah(saldo_sum('expense', date('Y-m-d'))) }}</td>
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

        <script src="{{ asset('js/chart_laporan_harian.js') }}"></script>
    @endpush
</x-master-layout>
