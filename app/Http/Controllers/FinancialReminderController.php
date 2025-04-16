<?php

namespace App\Http\Controllers;

use App\Models\FinancialReminder;
use Illuminate\Http\Request;

class FinancialReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = FinancialReminder::where('is_active', true)
            ->select('id', 'name', 'day_of_month', 'description', 'nominal', 'is_active')
            ->orderBy('day_of_month')
            ->get();
        $title = 'Pengingat Keuangan';

        return view('reminders.index', compact('reminders', 'title'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'day_of_month' => 'required|integer|min:1|max:31',
            'description' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
        ]);

        FinancialReminder::create($request->all());

        return redirect()->back()->with('success', 'Reminder berhasil ditambahkan!');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reminder = FinancialReminder::findOrFail($id);
        $reminder->delete();
        return redirect()->back()->with('success', 'Reminder berhasil dihapus.');
    }
}
