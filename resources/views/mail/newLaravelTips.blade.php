@component('mail::message')
<h1> Saiu um novo Email </h1>



@if ($user->name)
       {{  'entrou  ' . $user->name }}
@else
       {{  'não entrou' }}  
@endif


@component('mail::button', ['url' => 'https://zankh.com.br'])

    Verificar Emails Sendgrid!

@endcomponent

<p> Salve salve {{ $user->name }} !!!!     </p>


@endcomponent