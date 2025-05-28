<x-master-layout :title="$title">
    <button data-modal-target="buat-target-modal" data-modal-toggle="buat-target-modal"
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

                @if ($target->status !== 'withdraw')
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
                                <button data-modal-target="modal-cairkan{{ $target->id }}"
                                    data-modal-toggle="modal-cairkan{{ $target->id }}"
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
                @else
                    <h2 class="text-2xl font-bold text-blue-700 mt-5">{{ rupiah($target->target_amount) }} Berhasil
                        dicairkan
                    </h2>
                    <p class="text-lg text-green-700 font-bold">Selesai</p>
                @endif
            </div>
        @empty
            <div class="text-center text-lg text-gray-500">Data Kosong</div>
        @endforelse
    </div>


    @include('components.modal-delete')

    <!-- BUat target modal -->
    <div id="buat-target-modal" tabindex="-1" aria-hidden="true"
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
                        data-modal-hide="buat-target-modal">
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

    @foreach ($targetList as $target)
        <div id="modal-cairkan{{ $target->id }}" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="modal-cairkan{{ $target->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Keluar</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Kamu yakin cairkan target
                            ini?</h3>
                        <form action="{{ route('target-keuangan.update', $target->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button data-modal-hide="modal-cairkan{{ $target->id }}" type="submit"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Cairkan
                            </button>
                            <button data-modal-hide="modal-cairkan{{ $target->id }}" type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak,
                                batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

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
