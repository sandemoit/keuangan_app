<x-master-layout :title="$title">
    <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        <i class="fa-solid fa-plus"></i>
        Buat Target Kita
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        @forelse ($targetList as $target)
            <div class="bg-white border border-blue-200 rounded-2xl shadow-md p-5 w-full">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                        🎯 {{ $target->name }}
                    </h2>
                    <span class="text-sm text-gray-500 font-semibold">Rp {{ rupiah($target->target_amount) }}</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $target->progress }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mb-3">Progres {{ $target->progress }}%</p>

                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500">
                        *Silahkan input target di <a href="{{ route('transaksi') }}"
                            class="text-blue-600 underline">transaksi</a>
                    </span>
                    <div class="flex items-center gap-2">
                        @if ($target->progress > 0 && $target->status !== 'withdraw')
                            <button type="button"
                                class="inline-flex items-center gap-1 text-sm text-blue-700 bg-blue-100 hover:bg-blue-200 font-medium px-4 py-2 rounded-lg transition">
                                <i class="fas fa-money-bill-wave"></i> Cairkan
                            </button>
                        @endif

                        @if ($target->progress == 0)
                            <button data-modal-target="modal-delete" data-modal-toggle="modal-delete"
                                data-delete-url="{{ route('target-keuangan.destroy', $target->id) }}"
                                class="btn-delete inline-flex items-center gap-1 text-sm text-red-700 bg-red-100 hover:bg-red-200 font-medium px-4 py-2 rounded-lg transition">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-lg text-gray-500">Data Kosong</div>
        @endforelse
    </div>


    @include('components.modal-delete')

    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Buat target kita disini
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Keluar</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form class="space-y-4" action="{{ route('target-keuangan.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="target"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Target</label>
                            <input type="text" name="target" id="target"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Tabungan Umroh" required />
                        </div>
                        <div>
                            <label for="nominal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nominal</label>
                            <input type="text" name="nominal" id="nominal"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="5000000" required />
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan
                            Target</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
