@component('mail::message')
# ¡Hola {{ $user->name }}!

Has cambiado tu correo electrónico, por favor verifica la nueva dirección de correo usando el siguiente botón:

@component('mail::button', ['url' => route('users.verify', $user->verification_token)])
Confirmar nuevo correo electrónico
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent

