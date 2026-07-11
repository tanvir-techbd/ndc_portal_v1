<x-mail::message>
# You've been invited to the NDC Admin Panel

{{ $user->name }}, you've been invited as **{{ $user->role === 'super_admin' ? 'Super Admin' : 'Content Editor' }}**
on the National Data Center admin panel.

Click below to set your password and activate your account. This link expires in 7 days.

<x-mail::button :url="$inviteUrl">
Set Your Password
</x-mail::button>

If you weren't expecting this invite, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
