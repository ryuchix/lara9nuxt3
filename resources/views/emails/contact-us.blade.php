@component('mail::message')
# {{ $details['title'] }}

{{ $details['data']['name'] }} sent a message using the ANshell form.

Subject: {{ $details['data']['subject'] }}<br>
Name: {{ $details['data']['name'] }}<br>
Email: {{ $details['data']['email'] }}<br>
Phone: {{ $details['data']['phone'] }}<br>
Message: {{ $details['data']['message'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
