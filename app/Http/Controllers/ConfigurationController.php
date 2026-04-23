<?php

namespace App\Http\Controllers;

use App\Models\RcTypeSignal;
use App\Models\RcPoint;
use App\Models\GfdInspectionItem;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $typeSignals = RcTypeSignal::with(['points' => function ($query) {
            $query->orderBy('sequence');
        }])->orderBy('sequence')->get();
        $gfdItems = GfdInspectionItem::orderBy('sequence')->get();
        return view('configuration.index', compact('typeSignals', 'gfdItems'));
    }

    // RC Type Signals
    public function storeTypeSignal(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $signal = RcTypeSignal::create($request->only('name'));
        
        if ($request->expectsJson()) {
            $html = view('configuration.partials.type-signal-row', ['signal' => $signal])->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
        return back()->with('success', 'Type Signal berhasil ditambahkan.');
    }

    public function updateTypeSignal(Request $request, RcTypeSignal $typeSignal)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $typeSignal->update($request->only('name'));
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Type Signal berhasil diperbarui.']);
        }
        return back()->with('success', 'Type Signal berhasil diperbarui.');
    }

    public function destroyTypeSignal(RcTypeSignal $typeSignal)
    {
        $typeSignal->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Type Signal berhasil dihapus.']);
        }
        return back()->with('success', 'Type Signal berhasil dihapus.');
    }

    // RC Points
    public function storePoint(Request $request)
    {
        $request->validate([
            'type_signal_id' => 'required|exists:rc_type_signals,id',
            'name' => 'required|string|max:255',
        ]);
        $point = RcPoint::create($request->only('type_signal_id', 'name'));
        $signal = $point->typeSignal;

        if ($request->expectsJson()) {
            $html = view('configuration.partials.point-row', ['point' => $point, 'signal' => $signal])->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
        return back()->with('success', 'Point berhasil ditambahkan.');
    }

    public function updatePoint(Request $request, RcPoint $point)
    {
        $request->validate([
            'type_signal_id' => 'required|exists:rc_type_signals,id',
            'name' => 'required|string|max:255',
        ]);
        $point->update($request->only('type_signal_id', 'name'));
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Point berhasil diperbarui.']);
        }
        return back()->with('success', 'Point berhasil diperbarui.');
    }

    public function destroyPoint(RcPoint $point)
    {
        $point->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Point berhasil dihapus.']);
        }
        return back()->with('success', 'Point berhasil dihapus.');
    }

    // GFD Inspection Items
    public function storeGfdItem(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $item = GfdInspectionItem::create($request->only('name'));
        
        if ($request->expectsJson()) {
            $html = view('configuration.partials.gfd-item-row', ['item' => $item])->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
        return back()->with('success', 'Item Pemeriksaan berhasil ditambahkan.');
    }

    public function updateGfdItem(Request $request, GfdInspectionItem $gfdItem)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $gfdItem->update($request->only('name'));
        
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item Pemeriksaan berhasil diperbarui.']);
        }
        return back()->with('success', 'Item Pemeriksaan berhasil diperbarui.');
    }

    public function destroyGfdItem(GfdInspectionItem $gfdItem)
    {
        $gfdItem->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item Pemeriksaan berhasil dihapus.']);
        }
        return back()->with('success', 'Item Pemeriksaan berhasil dihapus.');
    }

    public function reorderTypeSignals(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $index => $id) {
            RcTypeSignal::where('id', $id)->update(['sequence' => $index]);
        }
        return response()->json(['success' => true]);
    }

    public function reorderPoints(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $index => $id) {
            RcPoint::where('id', $id)->update(['sequence' => $index]);
        }
        return response()->json(['success' => true]);
    }

    public function reorderGfdItems(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $index => $id) {
            GfdInspectionItem::where('id', $id)->update(['sequence' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
