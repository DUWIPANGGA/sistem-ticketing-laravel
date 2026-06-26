<x-mail::message>
# Ticket Updated: {{ $ticket->ticket_number }}

Hello {{ $ticket->creator->name }},

{{ $updateMessage }}

**Subject:** {{ $ticket->subject }}  
**Status:** {{ \App\Enums\TicketStatus::tryFrom($ticket->status)?->label() ?? ucfirst($ticket->status) }}  
**Priority:** {{ $ticket->priority_label }}  

<x-mail::button :url="route('tickets.show', $ticket->id)">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
