@component('mail::message')
# HNNW Beta Gebruikerspanel Feedback

We hebben de volgende feedback ontvangen via de feedback knop op het platform:<br>
<br>
<strong>Gebruiker:</strong> {{ $feedback->user->formatted_name }}<br>
<strong>Onderwerp:</strong> {{ $feedback->subject }}<br>
<strong>Feedback:</strong> {{ $feedback->message }}<br>
<br>
{!! $closingText !!} 
@endcomponent
