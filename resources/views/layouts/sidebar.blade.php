<!-- Sidebar -->
<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <h3 class="mb-4 text-xs leading-[20px] text-gray-400 uppercase">MENU</h3>
        <ul class="mb-6 flex flex-col gap-4">
            <li>
                <a href="{{ route('dashboard') }}" class="menu-item active-menu-item">
                    <i class="fas fa-home menu-icon"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi') }}" class="menu-item">
                    <i class="fas fa-exchange-alt menu-icon"></i>
                    <span class="ml-3">Transaksi</span>
                </a>
            </li>
            <li>
                <button type="button"
                    class="menu-item flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group"
                    aria-controls="dropdown-finance" data-collapse-toggle="dropdown-finance">
                    <i class="fas fa-money-bill-wave menu-icon"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Keuangan</span>
                    <i class="fas fa-chevron-down w-4 h-4"></i>
                </button>
                <ul id="dropdown-finance" class="hidden px-10">
                    <li>
                        <a href="#"
                            class="menu-dropdown-item items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Pemasukan</a>
                    </li>
                    <li>
                        <a href="#"
                            class="menu-dropdown-item items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Pengeluaran</a>
                    </li>
                </ul>
            </li>
            <li>
                <button type="button"
                    class="menu-item flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group"
                    aria-controls="dropdown-report" data-collapse-toggle="dropdown-report">
                    <i class="fas fa-chart-pie menu-icon"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Laporan</span>
                    <span
                        class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-green-200 rounded-full">New</span>
                    <i class="fas fa-chevron-down w-4 h-4"></i>
                </button>
                <ul id="dropdown-report" class="hidden px-10">
                    <li>
                        <a href="#"
                            class="menu-dropdown-item items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Harian</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.bulanan') }}"
                            class="menu-dropdown-item items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Bulanan</a>
                    </li>
                    <li>
                        <a href="#"
                            class="menu-dropdown-item items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100">Tahunan</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('category.index') }}" class="menu-item">
                    <i class="fas fa-tag menu-icon"></i>
                    <span class="ml-3">Kategori</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog menu-icon"></i>
                    <span class="ml-3">Pengaturan</span>
                </a>
            </li>
        </ul>

        <!-- Quick Stats -->
        <div class="pt-5 mt-5 space-y-2 border-t border-gray-200">
            <h3 class="mb-4 text-xs leading-[20px] text-gray-400 uppercase">RINGKASAN</h3>

            <!-- Total Balance -->
            <div class="p-4 bg-gradient-to-r from-blue-100 to-blue-50 rounded-lg">
                <p class="text-sm text-gray-500">Total Saldo</p>
                <h4 class="text-xl font-bold text-blue-800">{{ rupiah(saldo_sum('totalSaldo')) }}</h4>
            </div>

            <!-- Income -->
            <div class="flex items-center p-2 text-gray-900 rounded-lg group">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Pengeluaran</p>
                    <p class="text-sm font-medium">{{ rupiah(saldo_sum('totalExpense', date('Y-m'))) }}</p>
                </div>
            </div>

            <!-- Expense -->
            <div class="flex items-center p-2 text-gray-900 rounded-lg group">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-arrow-down text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-gray-500">Pemasukan</p>
                    <p class="text-sm font-medium">{{ rupiah(saldo_sum('totalIncome', date('Y-m'))) }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
