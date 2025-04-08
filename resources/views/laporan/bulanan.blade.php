<x-master-layout :title="$title">
    <div class="w-full grid grid-cols-2 gap-4 mt-6">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
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
                        <td class="px-4 py-2 text-right {{ $akumulasi < 0 ? 'text-red-700' : '' }}">{{ $akumulasi }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-2">Saldo akhir bulan</td>
                        <td class="px-4 py-2 text-right" colspan="2">Rp</td>
                        <td class="px-4 py-2 text-right"></td>
                        <td class="px-4 py-2 text-right">
                            {{ $saldoAkhirBulanIni }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <div id="legend-chart"></div>
        </div>
    </div>

    <div class="w-full grid grid-cols-2 gap-4 mt-6">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <div class="flex justify-between mb-3">
                <div class="flex justify-center items-center">
                    <i class="fa-solid fa-caret-down text-green-400 text-2xl mr-2"></i>
                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Pemasukan</h5>
                </div>
            </div>
            <!-- Donut Chart -->
            <div class="py-6" id="donut-chart-pemasukan"></div>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <div class="flex justify-between mb-3">
                <div class="flex justify-center items-center">
                    <i class="fa-solid fa-caret-up text-red-400 text-2xl mr-2"></i>
                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Pengeluaran</h5>
                </div>
            </div>
            <!-- Donut Chart -->
            <div class="py-6" id="donut-chart-pengeluaran"></div>
        </div>
    </div>

    <div class="w-full grid grid-cols-2 gap-4 mt-6">
        @if (!$kategoriIncome->isEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
                <table class="w-full text-sm text-left text-gray-700">
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
                </table>

                <div class="px-4 py-2 text-xs text-gray-500">
                    ** Kategori tanpa aktivitas disembunyikan.
                </div>
            </div>
        @endif

        @if (!$kateogirExpense->isEmpty())
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 content-center">
                <table class="w-full text-sm text-left text-gray-700">
                    <tbody>
                        @foreach ($kateogirExpense as $kategori)
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
                </table>

                <div class="px-4 py-2 text-xs text-gray-500">
                    ** Kategori tanpa aktivitas disembunyikan.
                </div>
            </div>
        @endif
    </div>

    @php
        $income = saldo_sum('income', date('Y-m'));
        $expense = saldo_sum('expense', date('Y-m'));
    @endphp

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
        <script>
            const pemasukan = {{ $income }};
            const pengeluaran = {{ $expense }};
            const kategoriLabelsPemasukan = {!! json_encode($labelsIncome) !!};
            const kategoriDataPemasukan = {!! json_encode($dataIncome) !!};
            const kategoriLabelsPengeluaran = {!! json_encode($labelsExpense) !!};
            const kategoriDataPengeluaran = {!! json_encode($dataExpense) !!};
        </script>

        <script src="{{ asset('js/chart_laporan.js') }}"></script>
    @endpush
</x-master-layout>
