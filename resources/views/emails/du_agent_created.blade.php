@component('mail::message')
# Hello {{ $name }},

You have been registered as a **District-Union Agent** for the **{{ $district }}** district.

Here are your login details:

@component('mail::panel')
**Email:** {{ $email }}  
**Password:** {{ $password }}
@endcomponent

@component('mail::button', ['url' => url('/login')])
Log in to your DU Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
