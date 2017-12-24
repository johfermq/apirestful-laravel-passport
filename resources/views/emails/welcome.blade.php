@component('mail::message')
# ¡Hola {{ $user->name }}!

Gracias por registrarte, por favor verifica la cuenta usando el siguiente botón:

@component('mail::button', ['url' => route('users.verify', $user->verification_token)])
Confirmar mi cuenta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent

