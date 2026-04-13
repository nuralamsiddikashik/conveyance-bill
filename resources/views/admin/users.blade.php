<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - User Approvals</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-slate-100">
    <div class="mx-auto max-w-5xl px-4 py-8">
      <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">User Approvals</h1>
          <p class="mt-1 text-sm text-slate-500">Approve new registrations before they can access the system.</p>
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

      <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Name</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Email</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Role</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
              <th class="px-4 py-3 text-left font-semibold text-slate-700">Registered</th>
              <th class="px-4 py-3 text-right font-semibold text-slate-700">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse ($users as $user)
              <tr>
                <td class="px-4 py-3 text-slate-900">{{ $user->name }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                    {{ $user->is_admin ? 'Admin' : 'User' }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  @if ($user->is_admin)
                    <span class="inline-flex rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">Admin</span>
                  @elseif ($user->approved_at === null)
                    <span class="inline-flex rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">Pending Approval</span>
                  @else
                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Approved</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-slate-600">
                  {{ $user->created_at?->format('d M, Y') }}
                </td>
                <td class="px-4 py-3 text-right">
                  @if (! $user->is_admin && $user->approved_at === null)
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                      @csrf
                      <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-700">Approve</button>
                    </form>
                  @else
                    <span class="text-xs text-slate-400">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">No users found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
