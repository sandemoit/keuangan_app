<x-master-layout :title="$title">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <!-- Total Saldo -->
        <div class="bg-blue-100 text-blue-900 rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <i class="fas fa-wallet"></i>
                Total Saldo
            </div>
            <div class="text-2xl font-bold mt-1">Rp {{ $totalSaldo }}</div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="bg-green-100 text-green-900 rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <i class="fas fa-arrow-down"></i>
                Total Pemasukan Bulan Ini
            </div>
            <div class="text-xl font-bold mt-1">Rp {{ $income }}</div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-red-100 text-red-900 rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-2 text-sm font-semibold">
                <i class="fas fa-arrow-up"></i>
                Total Pengeluaran Bulan Ini
            </div>
            <div class="text-xl font-bold mt-1">Rp {{ $expense }}</div>
        </div>

        <!-- Selisih Bulan Ini -->
        <div class="bg-yellow-100 text-yellow-900 rounded-xl shadow-sm p-4">
            <div class="text-sm font-semibold">Selisih Bulan Ini</div>
            <div class="text-xl font-bold mt-1">Rp {{ $selisih }}</div>
        </div>

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
        <div class="bg-white rounded-xl shadow-sm p-4">
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
                        Rp {{ number_format($reminder->nominal, 0, ',', '.') }}
                        (Tiap Tanggal {{ $reminder->day_of_month }})
                    </li>
                @empty
                    <li>Tidak ada tagihan yang perlu diingatkan.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-master-layout>
