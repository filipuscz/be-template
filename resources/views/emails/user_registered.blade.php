<x-mail::message>
# Welcome, {{ $user->name }}!

Thank you for registering with {{ config('app.name') }}. We are excited to have you on board!

<x-mail::button :url="config('app.url')">
Visit Application
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
