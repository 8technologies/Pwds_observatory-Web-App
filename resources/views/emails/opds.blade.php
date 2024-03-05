@component('mail::message')
# Hello

You have been made the Administrator of a Organisation Of Persons with disabilities <b> {{ $name }} </b>.
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