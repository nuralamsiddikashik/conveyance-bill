<?php

namespace App\Http\Controllers;

use App\Models\Conveyance;
use App\Models\ConveyanceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConveyanceController extends Controller
{
    /**
     * Show a fresh form for creating a conveyance.
     */
    public function create()
    {
        $today = now()->toDateString();

        return view('conveyance', [
            'mode' => 'create',
            'conveyance' => null,
            'rows' => [],
            'date' => $today,
        ]);
    }

    /**
     * Store or update a conveyance for a given date.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'rows' => ['required', 'string'],
        ]);

        $rows = json_decode($validated['rows'], true) ?: [];

        // Filter out completely empty rows
        $rows = array_values(array_filter($rows, function ($row) {
            return isset($row['from'], $row['to'], $row['amount'], $row['remarks']) &&
                (trim($row['from']) !== '' ||
                    trim($row['to']) !== '' ||
                    (float) ($row['amount'] ?? 0) > 0 ||
                    trim($row['remarks']) !== '');
        }));

        if (empty($rows)) {
            return back()
                ->withInput()
                ->withErrors(['rows' => 'Please add at least one conveyance row.']);
        }

        $total = 0;
        foreach ($rows as $row) {
            $total += (float) ($row['amount'] ?? 0);
        }

        DB::transaction(function () use ($validated, $rows, $total) {
            /** @var \App\Models\Conveyance $conveyance */
            $conveyance = Conveyance::create([
                'date' => $validated['date'],
                'total_amount' => $total,
            ]);

            foreach ($rows as $row) {
                $conveyance->items()->create([
                    'from_place' => $row['from'] ?? null,
                    'to_place' => $row['to'] ?? null,
                    'amount' => (float) ($row['amount'] ?? 0),
                    'remarks' => $row['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('conveyances.create')
            ->with('status', 'Conveyance saved successfully.');
    }

    /**
     * List conveyances grouped by date.
     */
    public function index()
    {
        $conveyances = Conveyance::orderByDesc('date')->get();

        return view('conveyance_index', [
            'conveyances' => $conveyances,
        ]);
    }

    /**
     * Show a specific conveyance by its date.
     */
    public function showByDate(string $date)
    {
        $conveyance = Conveyance::with('items')
            ->where('date', $date)
            ->firstOrFail();

        $rows = $conveyance->items->map(function (ConveyanceItem $item) {
            return [
                'from' => $item->from_place,
                'to' => $item->to_place,
                'amount' => (float) $item->amount,
                'remarks' => $item->remarks,
            ];
        })->values()->all();

        return view('conveyance', [
            'mode' => 'show',
            'conveyance' => $conveyance,
            'rows' => $rows,
            'date' => $conveyance->date->format('Y-m-d'),
        ]);
    }

    /**
     * Show a specific conveyance by id.
     */
    public function show(Conveyance $conveyance)
    {
        $conveyance->load('items');

        $rows = $conveyance->items->map(function (ConveyanceItem $item) {
            return [
                'from' => $item->from_place,
                'to' => $item->to_place,
                'amount' => (float) $item->amount,
                'remarks' => $item->remarks,
            ];
        })->values()->all();

        return view('conveyance', [
            'mode' => 'show',
            'conveyance' => $conveyance,
            'rows' => $rows,
            'date' => $conveyance->date->format('Y-m-d'),
        ]);
    }

    /**
     * Delete a conveyance and its items.
     */
    public function destroy(Conveyance $conveyance)
    {
        $conveyance->delete();

        return redirect()
            ->route('conveyances.index')
            ->with('status', 'Conveyance deleted successfully.');
    }
}

