@push('custom-css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet" />
@endpush
<x-master-layout :title="$title">

    <div id="accordion-collapse" data-accordion="collapse" class="mt-4">
        <h2 id="accordion-collapse-heading-1">
            <button type="button"
                class="flex items-center justify-between w-full p-5 font-medium rtl:text-right bg-white text-blue-800 shadow-sm rounded-t-xl dark:border-gray-700 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 gap-3"
                data-accordion-target="#accordion-collapse-body-1" aria-expanded="false"
                aria-controls="accordion-collapse-body-1">
                <span><i class="fa-regular fa-square-plus"></i> Buat Kategori Disini</span>
            </button>
        </h2>
        <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
            <div class="p-5 border border-t-0 rounded-b-xl border-gray-200 dark:border-gray-700 bg-white">
                <form class="w-full">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="group">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                Kategori</label>
                            <input type="text" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Gaji" required />
                        </div>
                        <div class="group">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_expense" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                </div>
                                <span
                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Pengeluaran?</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" id="btnSave"
                        class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <span class="btn-text">Simpan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="w-full bg-white rounded-xl rounded-t-none shadow-sm p-5">
        <table id="categoryTabel">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            Nama
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Type
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
                @foreach ($category as $category)
                    <tr>
                        <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $category->name }}
                        </td>
                        <td>
                            @if ($category->is_expense)
                                <span class="text-red-600"><i class="fas fa-arrow-up ml-1"></i> Pengeluaran</span>
                            @else
                                <span class="text-green-600"><i class="fas fa-arrow-down ml-1"></i> Pemasukan</span>
                            @endif
                        </td>
                        <td>
                            <button data-modal-target="modal-delete" data-modal-toggle="modal-delete"
                                data-delete-url="{{ route('category.destroy', $category->id) }}"
                                class="btn-delete text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('components.modal-delete')

    @push('custom-js')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
        <script>
            if (document.getElementById("categoryTabel") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#categoryTabel", {
                    searchable: true,
                    sortable: false
                });
            }
        </script>
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
        <script src="{{ asset('js/category.js') }}"></script>
    @endpush
</x-master-layout>
