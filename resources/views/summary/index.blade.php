<x-master-layout :title="$title">
    <!-- Generate Button -->
    <div class="mb-6">
        <button onclick="generateSummary()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-robot mr-2"></i>
            Generate AI Summary
        </button>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden">
        <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Sedang menganalisis data keuangan Anda...</p>
        </div>
    </div>

    <!-- Summary Results -->
    <div id="summary-container" class="hidden space-y-4">
        <!-- Summary Card -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800" id="summary-title">📄 Ringkasan</h3>
            </div>
            <div class="p-6">
                <div id="summary-content" class="space-y-2">
                    <!-- Summary content will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Recommendations Card -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 bg-green-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">💡 Rekomendasi</h3>
            </div>
            <div class="p-6">
                <ul id="recommendations-content" class="space-y-2">
                    <!-- Recommendations will be populated by JavaScript -->
                </ul>
            </div>
        </div>

        <!-- Analysis Card -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">📊 Analisis Tren</h3>
            </div>
            <div class="p-6">
                <div id="analysis-content" class="text-gray-700 leading-relaxed">
                    <!-- Analysis content will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-4">
            <p id="summary-footer" class="text-sm text-gray-500"></p>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="hidden">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <div class="text-red-600 mb-2">
                <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-red-800 mb-2">Oops! Terjadi Kesalahan</h3>
            <p class="text-red-600 mb-4">Gagal menghasilkan ringkasan. Silakan coba lagi.</p>
            <button onclick="generateSummary()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                Coba Lagi
            </button>
        </div>
    </div>

    @push('custom-js')
        <script>
            function generateSummary() {
                // Show loading state
                showLoading();

                fetch('/ai-summary', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displaySummary(data.data, data.footer);
                        } else {
                            showError();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError();
                    });
            }

            function showLoading() {
                document.getElementById('loading-state').classList.remove('hidden');
                document.getElementById('summary-container').classList.add('hidden');
                document.getElementById('error-state').classList.add('hidden');
            }

            function displaySummary(data, footer) {
                // Hide loading, show summary
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('summary-container').classList.remove('hidden');
                document.getElementById('error-state').classList.add('hidden');

                // Populate summary content
                const summaryContent = document.getElementById('summary-content');
                summaryContent.innerHTML = `
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Total Pemasukan:</span>
                        <span class="font-semibold text-green-600">${data.summary.content.income}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Total Pengeluaran:</span>
                        <span class="font-semibold text-red-600">${data.summary.content.expense}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="font-medium text-gray-700">Saldo:</span>
                        <span class="font-bold text-blue-600">${data.summary.content.balance}</span>
                    </div>
                `;

                // Populate recommendations
                const recommendationsContent = document.getElementById('recommendations-content');
                recommendationsContent.innerHTML = data.recommendations.content
                    .map(rec =>
                        `<li class="flex items-start"><span class="text-green-500 mr-2">•</span><span class="text-gray-700">${rec}</span></li>`
                    )
                    .join('');

                // Populate analysis
                const analysisContent = document.getElementById('analysis-content');
                analysisContent.textContent = data.analysis.content;

                // Set footer
                document.getElementById('summary-footer').textContent = footer;
            }

            function showError() {
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('summary-container').classList.add('hidden');
                document.getElementById('error-state').classList.remove('hidden');
            }
        </script>
    @endpush
</x-master-layout>
