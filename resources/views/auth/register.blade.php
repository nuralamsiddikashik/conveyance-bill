<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Conveyance Bill</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-md shadow-md p-6">
      <h1 class="text-xl font-semibold text-slate-900 mb-4">
        Register
      </h1>

      @if ($errors->any())
        <div class="mb-3 rounded border border-red-500 bg-red-50 px-3 py-2 text-sm text-red-800">
          <ul class="list-disc pl-4">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Name</label>
          <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            autofocus
            class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
          <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
          <input
            type="password"
            name="password"
            required
            class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password</label>
          <input
            type="password"
            name="password_confirmation"
            required
            class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          />
        </div>

        <div class="flex items-center justify-between text-xs">
          <span class="text-slate-700">
            Already have an account?
          </span>
          <a
            href="{{ route('login') }}"
            class="text-indigo-600 hover:text-indigo-800 font-semibold"
          >
            Login
          </a>
        </div>

        <button
          type="submit"
          class="mt-2 w-full rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
        >
          Register
        </button>
      </form>
    </div>
  </body>
</html>

