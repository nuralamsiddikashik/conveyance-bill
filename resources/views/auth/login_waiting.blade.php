<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Approval - Conveyance Bill</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-md shadow-md p-6 text-center">
      <h1 class="text-xl font-semibold text-slate-900 mb-3">Waiting for Admin Approval</h1>
      <p class="text-sm text-slate-600 mb-4">
        Your login request has been sent to the admin. This page will update automatically once it is approved.
      </p>

      <div id="status" class="mb-4 text-xs text-slate-500">Checking approval status...</div>

      <div class="flex items-center justify-center gap-2 text-xs">
        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Back to login</a>
      </div>
    </div>

    <script>
      const statusEl = document.getElementById('status');
      let attempts = 0;

      async function checkStatus() {
        attempts += 1;
        try {
          const response = await fetch('{{ route('login.waiting.status') }}', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const data = await response.json();

          if (data.status === 'approved' && data.redirect) {
            statusEl.textContent = 'Approved! Redirecting...';
            window.location.href = data.redirect;
            return;
          }

          if (data.status === 'rejected') {
            statusEl.textContent = 'Login request rejected. Please contact admin.';
            return;
          }

          if (data.status === 'missing' || data.status === 'blocked') {
            statusEl.textContent = 'Login request not found. Please try logging in again.';
            return;
          }

          statusEl.textContent = 'Still waiting for approval...';
        } catch (error) {
          statusEl.textContent = 'Unable to check status. Retrying...';
        }

        const delay = attempts < 10 ? 3000 : 6000;
        setTimeout(checkStatus, delay);
      }

      setTimeout(checkStatus, 1000);
    </script>
  </body>
</html>
