<x-mail::message>
# Thanks for reaching out, {{ $inquiry->full_name }}

We've received your message and our team will respond within one business day.

> {{ strlen($inquiry->message) > 200 ? substr($inquiry->message, 0, 200) . '...' : $inquiry->message }}

If your matter is urgent, please call the helpdesk directly: {{ setting('helpdesk_phone') }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
