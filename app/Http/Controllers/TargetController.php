<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Target;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TargetController extends Controller
{
    public function index()
    {
        $title = 'Target Keuangan';

        $targetList = Target::with('transactions') // load relasi biar efisien
            // ->where('status', 'open')
            ->select('id', 'name', 'target_amount', 'status', 'deleted_at')
            ->get()
            ->map(function ($target) {
                $totalTransaksi = $target->transactions->sum('amount');
                $progress = $target->target_amount > 0
                    ? round(($totalTransaksi / $target->target_amount) * 100)
                    : 0;

                $target->progress = $progress;
                $target->current_amount = $totalTransaksi;

                return $target;
            });

        return view('target.index', compact('title', 'targetList'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'target' => 'required|string|max:255',
                'nominal' => 'required|numeric|min:0',
            ]);

            Target::create([
                'name' => $request->target,
                'target_amount' => $request->nominal,
                'status' => 'open',
            ]);

            return redirect()->back()->with('success', 'Target berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan, silahkan coba lagi.');
        }
    }

    public function update(string $id)
    {
        DB::beginTransaction();

        try {
            $target = Target::findOrFail($id);
            $target->update(['status' => 'withdraw']);

            Transaction::create([
                'user_id' => 1,
                'category_id' => Category::where('is_expense', 0)->value('id'),
                'amount' => $target->target_amount,
                'date_trx' => Carbon::now()->format('Y-m-d'),
                'payment_method' => 'bank transfer',
                'type' => 'income',
                'description' => 'Cairkan Target ' . $target->name,
                'target_id' => $id
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Target berhasil diupdate menjadi withdraw.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan, silahkan coba lagi.');
        }
    }

    public function destroy(string $id)
    {
        $category = Target::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Target berhasil dihapus.');
    }
}
