<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $categories = Category::all();
        $statuses = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];
        $technicians = User::whereIn('role', ['technician', 'admin'])->get();

        return view('reports.index', compact('categories', 'statuses', 'technicians'));
    }

    public function chartData(Request $request): JsonResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = Ticket::with('creator', 'assignee');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('technician')) {
            $query->where('assigned_to', $request->technician);
        }

        $tickets = $query->get();

        $totalTickets = $tickets->count();
        $openCount = $tickets->where('status', 'open')->count();
        $resolvedCount = $tickets->where('status', 'resolved')->count();
        $closedCount = $tickets->where('status', 'closed')->count();
        $avgRating = $tickets->whereNotNull('rating')->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = $tickets->filter(function ($t) use ($date) {
                return $t->created_at->year == $date->year && $t->created_at->month == $date->month;
            })->count();
        }

        $statusLabels = [];
        $statusData = [];
        $statusColors = [];
        $colorMap = [
            'open' => '#3B82F6', 'in_progress' => '#6366F1', 'on_hold' => '#F59E0B',
            'resolved' => '#10B981', 'closed' => '#6B7280',
        ];
        foreach (['open', 'in_progress', 'on_hold', 'resolved', 'closed'] as $s) {
            $c = $tickets->where('status', $s)->count();
            if ($c > 0) {
                $statusLabels[] = str_replace('_', ' ', ucfirst($s));
                $statusData[] = $c;
                $statusColors[] = $colorMap[$s];
            }
        }

        $categoryLabels = [];
        $categoryData = [];
        $catCounts = $tickets->groupBy('category')->map->count()->sortDesc()->take(8);
        foreach ($catCounts as $cat => $c) {
            $categoryLabels[] = $cat;
            $categoryData[] = $c;
        }

        $priorityLabels = [];
        $priorityData = [];
        $priorityOrder = ['low', 'medium', 'high', 'urgent'];
        foreach ($priorityOrder as $p) {
            $c = $tickets->where('priority', $p)->count();
            if ($c > 0) {
                $priorityLabels[] = ucfirst($p);
                $priorityData[] = $c;
            }
        }

        $techRatings = User::whereIn('role', ['technician'])
            ->get()
            ->map(function ($tech) {
                $ratings = Ticket::where('assigned_to', $tech->id)
                    ->whereNotNull('rating')
                    ->pluck('rating');
                $resolved = Ticket::where('assigned_to', $tech->id)
                    ->whereIn('status', ['resolved', 'closed'])
                    ->count();
                return [
                    'name' => $tech->name,
                    'avg_rating' => $ratings->count() > 0 ? round($ratings->avg(), 1) : 0,
                    'total_rated' => $ratings->count(),
                    'total_resolved' => $resolved,
                ];
            })->sortByDesc('avg_rating')->values();

        $catRatings = Category::all()->map(function ($cat) {
            $ratings = Ticket::where('category', $cat->name)
                ->whereNotNull('rating')
                ->pluck('rating');
            $total = Ticket::where('category', $cat->name)->count();
            return [
                'name' => $cat->name,
                'avg_rating' => $ratings->count() > 0 ? round($ratings->avg(), 1) : 0,
                'total_rated' => $ratings->count(),
                'total_tickets' => $total,
            ];
        })->sortByDesc('avg_rating')->values();

        return response()->json([
            'summary' => [
                'total' => $totalTickets,
                'open' => $openCount,
                'resolved' => $resolvedCount,
                'closed' => $closedCount,
                'avg_rating' => $avgRating,
            ],
            'monthly' => ['labels' => $monthlyLabels, 'data' => $monthlyData],
            'status' => ['labels' => $statusLabels, 'data' => $statusData, 'colors' => $statusColors],
            'category' => ['labels' => $categoryLabels, 'data' => $categoryData],
            'priority' => ['labels' => $priorityLabels, 'data' => $priorityData],
            'tech_ratings' => $techRatings,
            'cat_ratings' => $catRatings,
        ]);
    }

    public function export(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $query = Ticket::with('creator', 'assignee');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('technician')) {
            $query->where('assigned_to', $request->technician);
        }

        $tickets = $query->get();

        $fileName = 'tickets_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Ticket Number', 'Subject', 'Category', 'Status', 'Priority', 'Creator', 'Assignee', 'SLA Due', 'Rating', 'Created At'];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->subject,
                    $ticket->category,
                    $ticket->status,
                    $ticket->priority,
                    $ticket->creator?->name ?? 'N/A',
                    $ticket->assignee?->name ?? 'N/A',
                    $ticket->sla_due_at?->format('Y-m-d H:i') ?? 'N/A',
                    $ticket->rating ?? 'N/A',
                    $ticket->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
