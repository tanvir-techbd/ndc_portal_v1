<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login – NDC Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Barlow+Condensed:wght@500;600;700&display=swap" rel="stylesheet"/>
  @vite(['resources/css/admin.css'])
</head>
<body style="margin:0;padding:0;font-family:var(--font-b);">

<div class="admin-login-bg">
  <div class="admin-login-card">
    <div class="admin-login-logo">
      <h1>NDC Admin Panel</h1>
      <p>Content Management System</p>
    </div>

    <div class="admin-login-badge">
      &#9888; Restricted Access — Authorised Personnel Only
    </div>

    @if (session('status'))
      <div class="admin-alert admin-alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}">
      @csrf
      <div class="al-form-group">
        <label for="adminEmail">Admin Email Address</label>
        <div class="al-input-wrap">
          <span class="al-input-icon">&#9993;</span>
          <input type="email" id="adminEmail" name="email" value="{{ old('email') }}" placeholder="admin@bcc.gov.bd" required autofocus/>
        </div>
        @error('email')<div class="admin-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="al-form-group">
        <label for="adminPw">Password</label>
        <div class="al-input-wrap">
          <span class="al-input-icon">&#128274;</span>
          <input type="password" id="adminPw" name="password" placeholder="Enter admin password" required/>
        </div>
        @error('password')<div class="admin-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="al-forgot"><a href="#">Forgot password?</a></div>

      <button type="submit" class="al-submit">
        Sign In to Admin Panel &nbsp; &#8594;
      </button>
    </form>

    <div class="al-footer">
      Not an admin? <a href="{{ url('/login') }}">Return to portal login</a>
    </div>
  </div>
</div>

</body>
</html>
