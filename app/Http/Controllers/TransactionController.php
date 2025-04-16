<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Target;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Transaction::with('category:id,name,is_expense')
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
            'kategori' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
            'payment_method' => 'required',
            'tipe' => 'required',
            'deskripsi' => 'required',
        ]);

        $validated['user_id'] = 1;

        Transaction::create([
            'user_id' => $validated['user_id'],
            'category_id' => $validated['kategori'],
            'amount' => $validated['nominal'],
            'date_trx' => Carbon::createFromFormat('d-m-Y', $validated['tanggal'])->format('Y-m-d'),
            'payment_method' => $validated['payment_method'],
            'type' => $validated['tipe'],
            'description' => $validated['deskripsi'],
            'target_id' => $request->target_keuangan
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dibuat']);
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
            'deskripsi' => 'required',
        ]);

        $transaction = Transaction::findOrFail($id);

        $transaction->update([
            'category_id' => $validated['kategori'],
            'target_id' => $request->target_keuangan,
            'amount' => $validated['nominal'],
            'date_trx' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
            'payment_method' => $validated['payment_method'],
            'type' => $validated['tipe'],
            'description' => $validated['deskripsi'],
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
