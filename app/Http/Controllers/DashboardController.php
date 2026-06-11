<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $query = Ticket::query();

        if (!in_array($user->role, ['admin', 'technician'])) {
            $query->where('user_id', $user->id);
        }

        $totalTickets = (clone $query)->count();
        $openTickets = (clone $query)->where('status', 'open')->count();
        $resolvedTickets = (clone $query)->where('status', 'resolved')->count();
        $slaBreachedTickets = (clone $query)
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();

        $assignedTickets = collect();
        if (in_array($user->role, ['admin', 'technician'])) {
            $assignedTickets = Ticket::where('assigned_to', $user->id)
                ->whereNotIn('status', ['resolved', 'closed'])
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'asc')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalTickets', 'openTickets', 'resolvedTickets',
            'slaBreachedTickets', 'assignedTickets'
        ));
    }

    public function chartData(): JsonResponse
    {
        $user = Auth::user();
        $baseQuery = Ticket::query();

        if (!in_array($user->role, ['admin', 'technician'])) {
            $baseQuery->where('user_id', $user->id);
        }

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $label = $date->format('M Y');
            $monthlyLabels[] = $label;
            $monthlyData[] = (clone $baseQuery)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        $statusLabels = [];
        $statusData = [];
        $statusColors = [];
        $statusColorMap = [
            'open' => '#3B82F6',
            'in_progress' => '#6366F1',
            'on_hold' => '#F59E0B',
            'resolved' => '#10B981',
            'closed' => '#6B7280',
        ];
        $statuses = ['open', 'in_progress', 'on_hold', 'resolved', 'closed'];
        foreach ($statuses as $status) {
            $count = (clone $baseQuery)->where('status', $status)->count();
            if ($count > 0) {
                $statusLabels[] = str_replace('_', ' ', ucfirst($status));
                $statusData[] = $count;
                $statusColors[] = $statusColorMap[$status];
            }
        }

        $categoryQuery = (clone $baseQuery)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
        $categoryLabels = $categoryQuery->pluck('category')->toArray();
        $categoryData = $categoryQuery->pluck('total')->toArray();

        $priorityQuery = (clone $baseQuery)
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->orderByDesc('total')
            ->get();
        $priorityLabels = $priorityQuery->pluck('priority')->map(fn($p) => ucfirst($p))->toArray();
        $priorityData = $priorityQuery->pluck('total')->toArray();

        return response()->json([
            'monthly' => ['labels' => $monthlyLabels, 'data' => $monthlyData],
            'status' => ['labels' => $statusLabels, 'data' => $statusData, 'colors' => $statusColors],
            'category' => ['labels' => $categoryLabels, 'data' => $categoryData],
            'priority' => ['labels' => $priorityLabels, 'data' => $priorityData],
        ]);
    }
}
