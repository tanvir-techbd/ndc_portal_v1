<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Set Your Password – NDC Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;600;700&display=swap" rel="stylesheet"/>
  @vite(['resources/css/admin.css'])
</head>
<body style="margin:0;padding:0;font-family:var(--font-b);">

<div class="admin-login-bg">
  <div class="admin-login-card">
    <div class="admin-login-logo">
      <h1>NDC Admin Panel</h1>
      <p>Set Your Password</p>
    </div>

    <div class="admin-login-badge">
      &#128100; Welcome, {{ $user->name }} — invited as {{ $user->role === 'super_admin' ? 'Super Admin' : 'Content Editor' }}
    </div>

    <form method="POST" action="{{ route('admin.invite.accept.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}"/>

      <div class="al-form-group">
        <label for="newPw">New Password</label>
        <div class="al-input-wrap">
          <span class="al-input-icon">&#128274;</span>
          <input type="password" id="newPw" name="password" placeholder="Minimum 8 characters" required/>
        </div>
        @error('password')<div class="admin-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="al-form-group">
        <label for="newPwConfirm">Confirm Password</label>
        <div class="al-input-wrap">
          <span class="al-input-icon">&#128274;</span>
          <input type="password" id="newPwConfirm" name="password_confirmation" placeholder="Re-enter password" required/>
        </div>
      </div>

      <button type="submit" class="al-submit">
        Set Password &amp; Activate Account &nbsp; &#8594;
      </button>
    </form>
  </div>
</div>

</body>
</html>
