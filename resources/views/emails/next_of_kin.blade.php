@component('mail::message')
# Hello

An account has been created for <b>{{ $name }}</b> on <b>{{ config('app.name') }} </b>.
<br> Please find their login details below.
<br><br>

Email: <b>{{ $email }} </b>

Password: <b> {{ $password }} </b>

@component('mail::button', ['url' => url('/')])
Click Here!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent