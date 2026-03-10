<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function export()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $tickets = Ticket::with('creator', 'assignee')->get();

        $fileName = 'tickets_report_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Ticket Number', 'Subject', 'Category', 'Status', 'Priority', 'Creator', 'Assignee', 'SLA Due', 'Created At'];

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                $row['Ticket Number']  = $ticket->ticket_number;
                $row['Subject']        = $ticket->subject;
                $row['Category']       = $ticket->category;
                $row['Status']         = $ticket->status;
                $row['Priority']       = $ticket->priority;
                $row['Creator']        = $ticket->creator ? $ticket->creator->name : 'N/A';
                $row['Assignee']       = $ticket->assignee ? $ticket->assignee->name : 'N/A';
                $row['SLA Due']        = $ticket->sla_due_at ? $ticket->sla_due_at->format('Y-m-d H:i') : 'N/A';
                $row['Created At']     = $ticket->created_at->format('Y-m-d H:i');

                fputcsv($file, array($row['Ticket Number'], $row['Subject'], $row['Category'], $row['Status'], $row['Priority'], $row['Creator'], $row['Assignee'], $row['SLA Due'], $row['Created At']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
