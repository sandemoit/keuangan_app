<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index()
    {
        return view('summary.index', [
            'title' => 'Ringkasan Keuangan Bulanan',
        ]);
    }

    public function generateSummary(Request $request)
    {
        try {
            // Get transactions for this month
            $transactionsThisMonth = Transaction::whereYear('date_trx', now()->year)
                ->whereMonth('date_trx', now()->month)
                ->latest('date_trx')
                ->take(100)
                ->get();

            // Get transactions for last month
            $transactionsLastMonth = Transaction::whereYear('date_trx', now()->subMonth()->year)
                ->whereMonth('date_trx', now()->subMonth()->month)
                ->latest('date_trx')
                ->take(100)
                ->get();

            // Calculate totals for this month
            $totalIncome = $transactionsThisMonth->where('type', 'pemasukan')->sum('amount');
            $totalExpense = $transactionsThisMonth->where('type', 'pengeluaran')->sum('amount');

            // Format transactions
            $promptData = $transactionsThisMonth->map(function ($t) {
                $amount = number_format($t->amount, 0, ',', '.');
                return "{$t->type}: Rp {$amount} ({$t->category->name})";
            })->implode("\n");

            if ($transactionsLastMonth->isNotEmpty()) {
                $promptData .= "\n\nData bulan lalu:\n" . $transactionsLastMonth->map(function ($t) {
                    $amount = number_format($t->amount, 0, ',', '.');
                    return "{$t->type}: Rp {$amount} ({$t->category->name})";
                })->implode("\n");
            }

            // Create prompt
            $prompt = <<<PROMPT
            Analisis data transaksi keuangan dan berikan ringkasan dalam format yang rapi dan terstruktur.

            Format yang diinginkan:
            1. Bagian Ringkasan: tampilkan total pemasukan dan pengeluaran dengan format mata uang Indonesia
            2. Bagian Rekomendasi: berikan 2-3 saran praktis untuk pengelolaan keuangan
            3. Bagian Analisis Tren: analisis pola pengeluaran dan pemasukan

            Gunakan bahasa Indonesia yang profesional dan mudah dipahami. Fokus pada insight yang actionable.

            Data transaksi bulan ini:
            $promptData
            PROMPT;

            // Generate from Gemini
            $response = Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);

            // Parse and format the response
            $aiResponse = $response->text();

            // Format the response into structured data
            $formattedResponse = $this->formatAIResponse($aiResponse, $totalIncome, $totalExpense);

            return response()->json([
                'success' => true,
                'data' => $formattedResponse,
                'footer' => 'Powered by AI - Masa: ' . now()->format('d M Y H:i') . ' WIB'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghasilkan ringkasan. Coba lagi nanti.',
                'footer' => 'Powered by AI - Error'
            ], 500);
        }
    }

    private function formatAIResponse($aiResponse, $totalIncome, $totalExpense)
    {
        // Extract sections from AI response or create structured format
        $sections = [
            'summary' => [
                'title' => '📄 Ringkasan',
                'content' => [
                    'income' => 'Total Pemasukan: Rp ' . number_format($totalIncome, 0, ',', '.'),
                    'expense' => 'Total Pengeluaran: Rp ' . number_format($totalExpense, 0, ',', '.'),
                    'balance' => 'Saldo: Rp ' . number_format($totalIncome - $totalExpense, 0, ',', '.')
                ]
            ],
            'recommendations' => [
                'title' => '💡 Rekomendasi',
                'content' => $this->extractRecommendations($aiResponse)
            ],
            'analysis' => [
                'title' => '📊 Analisis Tren',
                'content' => $this->extractAnalysis($aiResponse)
            ]
        ];

        return $sections;
    }

    private function extractRecommendations($text)
    {
        // Simple extraction logic - you can make this more sophisticated
        $recommendations = [];

        // Look for bullet points or numbered lists in the AI response
        if (preg_match_all('/[•\-\*]\s*(.+?)(?=\n|$)/m', $text, $matches)) {
            $recommendations = array_slice($matches[1], 0, 3); // Take first 3
        }

        // Fallback recommendations if extraction fails
        if (empty($recommendations)) {
            $recommendations = [
                'Optimalisasi pengeluaran untuk kategori dengan nilai tertinggi',
                'Pastikan pengeluaran untuk kategori Makanan & Minuman tetap terkendali',
                'Pertimbangkan untuk membuat anggaran bulanan yang lebih terstruktur'
            ];
        }

        return $recommendations;
    }

    private function extractAnalysis($text)
    {
        // Extract analysis section or provide default
        if (preg_match('/analisis|tren/i', $text)) {
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                if (preg_match('/analisis|tren/i', $line)) {
                    // Get the next few lines as analysis
                    $key = array_search($line, $lines);
                    $analysis = array_slice($lines, $key + 1, 3);
                    return implode(' ', array_filter($analysis));
                }
            }
        }

        return 'Tren keuangan pada bulan ini menunjukkan pola pengeluaran yang perlu dioptimalkan, terutama pada kategori dengan frekuensi tinggi.';
    }
}
