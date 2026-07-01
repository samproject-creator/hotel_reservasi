<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogExport;

class ActivityLogController extends Controller
{
    /**
     * Halaman utama activity log dengan filter dan pagination.
     * Hanya dapat diakses oleh Admin.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('module')) {
            $query->byModule($request->module);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->byTanggal($request->dari, $request->sampai);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs    = $query->paginate(20)->withQueryString();
        $users   = User::orderBy('name')->get();
        $modules = ActivityLog::distinct()->pluck('module')->sort()->values();
        $actions = ActivityLog::distinct()->pluck('action')->sort()->values();

        return view('activity_log.index', compact('logs', 'users', 'modules', 'actions'));
    }

    /**
     * Detail satu log dengan tampilan old_data vs new_data.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity_log.show', compact('activityLog'));
    }

    /**
     * Export log ke Excel.
     */
    public function exportExcel(Request $request)
    {
        ActivityLogService::logExport('activity_log', 'Excel', 'Export activity log');

        return Excel::download(
            new ActivityLogExport($request->all()),
            'activity-log-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    /**
     * Export log ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->byTanggal($request->dari, $request->sampai);
        }

        if ($request->filled('module')) {
            $query->byModule($request->module);
        }

        $logs = $query->take(200)->get(); // batasi 200 baris untuk PDF

        ActivityLogService::logExport('activity_log', 'PDF', 'Export activity log');

        $pdf = Pdf::loadView('activity_log.pdf', compact('logs'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('activity-log-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Hapus log lama (hanya admin, untuk keperluan maintenance).
     * Hapus log yang lebih dari X hari.
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'hari' => 'required|integer|min:30|max:365',
        ]);

        $hapus = ActivityLog::where('created_at', '<', now()->subDays($request->hari))->delete();

        ActivityLogService::log(
            'deleted', 'activity_log', null,
            'Menghapus ' . $hapus . ' log lama (lebih dari ' . $request->hari . ' hari)',
        );

        return back()->with('success', $hapus . ' entri log lama berhasil dihapus.');
    }
}
