<x-mail::message>
# You have been invited

**{{ $company->company_name }}** has created an employee account for you on **{{ config('app.name') }}**.

Use the credentials below to sign in. Please change your password after logging in.

<x-mail::panel>
**Login:** [{{ $loginUrl }}]({{ $loginUrl }})  
**Email:** {{ $user->email }}  
**Temporary password:** `{{ $plainPassword }}`
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Sign in
</x-mail::button>

If you were not expecting this message, you can ignore it or contact your employer.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
