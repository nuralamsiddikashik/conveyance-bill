<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - User Activity</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-4 py-8">
      <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">User Activity</h1>
          <p class="mt-1 text-sm text-slate-500">Recent logins and page visits captured for admin review.</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Users</a>
            <a href="{{ route('admin.activity.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Activity</a>
            <a href="{{ route('admin.deletions.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Deletions</a>
            <a href="{{ route('admin.login-requests.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Login Requests</a>
          </div>
          <a href="{{ route('conveyances.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Back to History</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">Logout</button>
          </form>
        </div>
      </div>

      <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Time</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">User</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Event</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Method</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Path</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">IP</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Device</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse ($logs as $log)
              <tr>
                <td class="px-4 py-3 text-slate-600">{{ $log->created_at?->format('d M, Y H:i') }}</td>
                <td class="px-4 py-3 text-slate-900">{{ $log->user?->name ?? 'Unknown' }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($log->event) }}</span>
                </td>
                <td class="px-4 py-3 text-slate-600">{{ $log->method }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $log->path }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $log->ip_address }}</td>
                <td class="px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($log->user_agent ?? '-', 60) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">No activity logs yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $logs->links() }}
      </div>
    </div>
  </body>
</html>
