<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaMessageLog;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsAppGatewayController extends Controller
{
    /**
     * Display WhatsApp Gateway dashboard & logs audit.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = WaMessageLog::latest();
        if ($status) {
            $query->where('status', $status);
        }

        $logs = $query->paginate(15)->withQueryString();

        $totalSent = WaMessageLog::where('status', 'sent')->count();
        $totalFailed = WaMessageLog::where('status', 'failed')->count();
        $totalQueued = WaMessageLog::where('status', 'queued')->count();

        return view('admin.whatsapp.index', compact('logs', 'totalSent', 'totalFailed', 'totalQueued', 'status'));
    }

    /**
     * Dispatch test WhatsApp message.
     */
    public function testSend(Request $request, WhatsAppGatewayService $gateway): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        $log = $gateway->sendRaw($validated['phone'], $validated['message'], 'admin_manual_test');

        return redirect()->route('admin.whatsapp.index')->with('success', "Pesan uji coba berhasil diproses ke {$validated['phone']} (Status: {$log->status}).");
    }
}
