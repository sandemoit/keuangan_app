<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Target;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Transaction::with(['category:id,name,is_expense', 'target:id,name,target_amount'])
            ->select('id', 'category_id', 'amount', 'date_trx', 'payment_method', 'type', 'description')
            ->orderByDesc('date_trx');

        if (request('start') && request('end')) {
            $start = Carbon::createFromFormat('d-m-Y', request('start'))->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', request('end'))->endOfDay();

            $query->whereBetween('date_trx', [$start, $end]);
        }

        $transaksi = $query->get();
        $title = 'Catat Transaksi';
        $category = Category::select('id', 'name')->get();

        $targetKeuangan = Target::where('status', 'open')->get();

        return view('transaksi.index', compact('title', 'category', 'transaksi', 'targetKeuangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nominal' => 'required',
            'tanggal' => 'required',
            'payment_method' => 'required',
            'tipe' => 'required',
        ]);

        try {
            DB::beginTransaction();

            Transaction::create([
                'user_id' => 1,
                'category_id' => request('kategori') ?? 3,
                'amount' => $validated['nominal'],
                'date_trx' => Carbon::createFromFormat('d-m-Y', $validated['tanggal'])->format('Y-m-d'),
                'payment_method' => $validated['payment_method'],
                'type' => $validated['tipe'] == 'target' ? 'expense' : $validated['tipe'],
                'description' => $request->deskripsi,
                'target_id' => $request->tipe == 'target' ? $request->target_keuangan : null
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dibuat']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat transaksi'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kategori' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
            'payment_method' => 'required',
            'tipe' => 'required',
        ]);

        $transaction = Transaction::findOrFail($id);

        $transaction->update([
            'category_id' => $validated['kategori'],
            'target_id' => $request->target_keuangan,
            'amount' => $validated['nominal'],
            'date_trx' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
            'payment_method' => $validated['payment_method'],
            'type' => $validated['tipe'],
            'description' => $request->deskripsi,
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaction::findOrFail($id);
        $transaksi->delete();
        return redirect()->back()->with('message', 'Berhasil menghapus data.');
    }
}
