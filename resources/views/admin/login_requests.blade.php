<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Login Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-4 py-8">
      <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Login Requests</h1>
          <p class="mt-1 text-sm text-slate-500">Approve user logins that are outside the 8-hour window.</p>
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

      @if (session('status'))
        <div class="mb-4 rounded border border-green-500 bg-green-50 px-3 py-2 text-sm text-green-800">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-4 rounded border border-red-500 bg-red-50 px-3 py-2 text-sm text-red-800">
          <ul class="list-disc pl-4">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="mb-8">
        <h2 class="mb-3 text-lg font-semibold text-slate-900">Pending Requests</h2>
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
          <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Requested</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">User</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">IP</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Device</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse ($pending as $request)
                <tr>
                  <td class="px-4 py-3 text-slate-600">{{ $request->created_at?->format('d M, Y H:i') }}</td>
                  <td class="px-4 py-3 text-slate-900">{{ $request->user?->name ?? 'Unknown' }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ $request->request_ip }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($request->request_user_agent ?? '-', 60) }}</td>
                  <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <form method="POST" action="{{ route('admin.login-requests.approve', $request) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-700">Approve</button>
                      </form>
                      <form method="POST" action="{{ route('admin.login-requests.reject', $request) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-md border border-rose-200 bg-white px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">Reject</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">No pending login requests.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h2 class="mb-3 text-lg font-semibold text-slate-900">Recent Decisions</h2>
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
          <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">User</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">IP</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Device</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Decided By</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Decided At</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse ($history as $request)
                <tr>
                  <td class="px-4 py-3">
                    @if ($request->status === 'approved')
                      <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Approved</span>
                    @else
                      <span class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">Rejected</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-slate-900">{{ $request->user?->name ?? 'Unknown' }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ $request->request_ip }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($request->request_user_agent ?? '-', 60) }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ $request->approver?->name ?? $request->rejecter?->name ?? '—' }}</td>
                  <td class="px-4 py-3 text-slate-600">
                    {{ ($request->approved_at ?? $request->rejected_at)?->format('d M, Y H:i') }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">No login decisions yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
