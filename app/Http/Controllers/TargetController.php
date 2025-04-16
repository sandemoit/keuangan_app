<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index()
    {
        $title = 'Target Keuangan';

        $targetList = Target::with('transactions') // load relasi biar efisien
            ->where('status', 'open')
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

    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        $category = Target::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Target berhasil dihapus.');
    }
}
