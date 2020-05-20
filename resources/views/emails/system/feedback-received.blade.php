@component('mail::message')
# HNNW Beta Feedback

We hebben de volgende feedback ontvangen via de feedback knop op het platform:<br>
<br>
@if ($feedback->type == "bug_report")
    <strong>Type:</strong> Bug Report<br>
    <strong>Gebruiker:</strong>{{ $feedback->user->formatted_name }}<br>
    <strong>Pagina URL:</strong> <a href="{{ $feedback->page_url }}">{{ $feedback->page_url }}</a><br>
    <strong>Ernst:</strong> {{ $feedback->severity }}/3<br>
    <strong>Rapport:</strong> {{ $feedback->report }}<br>
@else
    <strong>Type:</strong> Algemene feedback<br>
    <strong>Gebruiker:</strong> {{ $feedback->user->formatted_name }}<br>
    <strong>Pagina URL:</strong> <a href="{{ $feedback->page_url }}">{{ $feedback->page_url }}</a><br>
    <strong>Onderwerp:</strong> {{ $feedback->subject }}<br>
    <strong>Feedback:</strong> {{ $feedback->message }}<br>
@endif
<br>

{!! $closingText !!} 
@endcomponent
