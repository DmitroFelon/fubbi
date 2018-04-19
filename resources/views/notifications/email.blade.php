@component('mail::message')
{{-- Greeting --}}
@if (! empty($mailMessage->greeting))
    # {!! $mailMessage->greeting !!}
@else
    @if ($mailMessage->level == 'error')
        # Whoops!
    @else
        # Hello!
    @endif
@endif

{{-- Intro Lines --}}
@foreach ($mailMessage->introLines as $line)
    {!! $line !!}

@endforeach

{{-- Action Button --}}
@isset($mailMessage->actionText)
<?php
switch ($mailMessage->level) {
    case 'success':
        $color = 'green';
        break;
    case 'error':
        $color = 'red';
        break;
    default:
        $color = 'blue';
}
?>
@component('mail::button', ['url' => $mailMessage->actionUrl, 'color' => $color])
{!! $mailMessage->actionText !!}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($mailMessage->outroLines as $line)
    {!! $line !!}

@endforeach

{{-- Salutation --}}
Thank you!

<br/>

The Fubbi Team

{{-- Subcopy --}}
@isset($mailMessage->actionText)
@component('mail::subcopy')
If you’re having trouble clicking the "{!! $mailMessage->actionText !!}" button, copy and paste the URL below
into your web browser: [{!! $mailMessage->actionUrl !!}]({!! $mailMessage->actionUrl !!})
@endcomponent
@endisset
@endcomponent
