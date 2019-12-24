@component('mail::message')
# Dear {{ $request->name }},
Thanks for you message.<br>
We'll contact you as soon as possible.

<hr>
<b>Your name:</b> {{ $request->name }}<br>
<b>Your email:</b> {{ $request->email }}<br>
<b>Your message:</b><br>{{ $request->message }}

Thanks,<br>
{{ env('APP_NAME') }}
@endcomponent
