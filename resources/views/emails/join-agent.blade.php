@component('mail::message')
# {{ $details['title'] }}

{{ $details['data']['name'] }} sent a message using the ANshell form.

Name: {{ $details['data']['name'] }}<br>
Email: {{ $details['data']['email'] }}<br>
Phone: {{ $details['data']['phone'] }}<br>
State: {{ $details['data']['state'] }}<br>
Zip: {{ $details['data']['zip'] }}<br>
License Number: {{ $details['data']['license_number'] }}<br>
License Rank: {{ $details['data']['license_rank'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
