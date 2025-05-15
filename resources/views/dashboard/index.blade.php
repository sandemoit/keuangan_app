<x-master-layout :title="$title">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-white rounded-lg shadow-sm p-6">
        <!-- Total Saldo -->
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-wallet text-green-600"></i>
                </div>
                <div>
                    <div class="text-sm font-semibold text-black">Total Saldo</div>
                    <div class="text-lg font-bold mt-1">Rp {{ $totalSaldo }}</div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-arrow-down text-blue-600"></i>
                </div>
                <div>
                    <div class="text-sm font-semibold text-black">Total Pemasukan Bulan Ini</div>
                    <div class="text-lg font-bold mt-1">Rp {{ $income }}</div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
                <div>
                    <div class="text-sm font-semibold text-black">Total Pengeluaran Bulan Ini</div>
                    <div class="text-lg font-bold mt-1">Rp {{ $expense }}</div>
                </div>
            </div>
        </div>

        <!-- Selisih Bulan Ini -->
        {{-- <div class="border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-balance-scale text-yellow-600"></i>
                </div>
                <div>
                    <div class="text-sm font-semibold text-black">Selisih Bulan Ini</div>
                    <div class="text-lg font-bold mt-1">Rp {{ $selisih }}</div>
                </div>
            </div>
        </div> --}}

        <!-- 5 Kategori Pengeluaran Tertinggi -->
        <div class="bg-purple-100 text-purple-900 rounded-xl shadow-sm p-4">
            <div class="text-sm font-semibold mb-2">Top 5 Kategori Pengeluaran Bulan Ini</div>
            <ul class="text-sm space-y-1">
                @forelse($topExpenseCategories as $category)
                    <li>
                        • {{ $category->name }} -
                        Rp {{ number_format($category->transactions_sum_amount, 0, ',', '.') }}
                    </li>
                @empty
                    <li>Tidak ada data pengeluaran bulan ini.</li>
                @endforelse
            </ul>
        </div>

        <!-- Transaksi Terakhir -->
        <div class="bg-green-100 text-green-900 rounded-xl shadow-sm p-4">
            <div class="text-sm font-semibold mb-2">Transaksi Terakhir</div>
            <ul class="text-sm space-y-1">
                @forelse($recentTransactions as $trx)
                    @php
                        $icon = $trx->type === 'income' ? '🟢' : '🔴';
                    @endphp
                    <li>
                        {{ $icon }} Rp {{ number_format($trx->amount, 0, ',', '.') }} -
                        {{ $trx->category->name ?? 'Tidak diketahui' }}
                        ({{ \Carbon\Carbon::parse($trx->tanggal)->format('d M') }})
                    </li>
                @empty
                    <li>Tidak ada transaksi terbaru.</li>
                @endforelse
            </ul>
        </div>

        <!-- Reminder Tagihan -->
        <div class="bg-orange-100 text-orange-900 rounded-xl shadow-sm p-4">
            <div class="text-sm font-semibold mb-2">Reminder Tagihan Bulan Ini</div>
            <ul class="text-sm space-y-1">
                @forelse($reminders as $reminder)
                    <li>
                        • {{ $reminder->name }} -
                        Rp {{ rupiah($reminder->nominal) }}
                        (Tiap Tanggal {{ $reminder->day_of_month }})
                    </li>
                @empty
                    <li>Tidak ada tagihan yang perlu diingatkan.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-master-layout>
