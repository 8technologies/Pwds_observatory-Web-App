@component('mail::message')
# Hello

An account has been created for you on the <b>{{ config('app.name') }} </b>.
<br> Please find your login details below.
<br><br>

Email: <b>{{ $email }} </b>

Password: <b> {{ $password }} </b>

@component('mail::button', ['url' => url('/')])
Click Here!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent