<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance History</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-slate-100">
    <div class="mx-auto max-w-5xl px-4 py-8">
      <div class="mb-6 flex items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Conveyance History</h1>
          <p class="mt-1 text-sm text-slate-500">A timeline of submitted conveyances — quick glance and actions.</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="hidden sm:flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">Total: <span class="ml-2 font-semibold text-slate-900">{{ $conveyances->count() }}</span></div>
          <a href="{{ route('conveyances.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-indigo-700">+ New Entry</a>

          @auth
            <div class="flex items-center gap-2">
              <span class="hidden sm:inline-block text-sm text-slate-700">{{ auth()->user()->name }}</span>
              @if (auth()->user()->is_admin)
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Admin</a>
              @endif
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ml-1 inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Logout</button>
              </form>
            </div>
          @endauth
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

      @if ($conveyances->isEmpty())
        <div class="rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-600">
          No conveyance records yet. Create today&apos;s conveyance from the entry page.
        </div>
      @else
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($conveyances as $conveyance)
            <article class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm hover:shadow-md transition-shadow">
              <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-500 to-indigo-300"></div>
              <div class="ml-3 flex items-start justify-between gap-4">
                <div class="flex-1">
                  <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div>
                      <div class="text-sm font-semibold text-slate-900">{{ $conveyance->date->format('d M, Y') }}</div>
                      <div class="mt-1 text-xs text-slate-500">Submitted {{ $conveyance->created_at->diffForHumans() }}</div>
                    </div>
                  </div>
                  <div class="mt-4 flex items-center justify-between gap-4">
                    <div>
                      <div class="text-xs text-slate-500">Total Amount</div>
                      <div class="mt-1 text-lg font-semibold text-slate-900">৳ {{ number_format($conveyance->total_amount, 2) }}</div>
                    </div>
                    <div class="text-right">
                      <div class="text-xs text-slate-500">Items</div>
                      <div class="mt-1 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $conveyance->items_count ?? $conveyance->items->count() }}</div>
                    </div>
                  </div>
                  @if (auth()->user()->is_admin)
                    <div class="mt-3 text-xs text-slate-500">
                      Owner: <span class="font-semibold text-slate-700">{{ $conveyance->user?->name ?? 'Unknown' }}</span>
                    </div>
                  @endif
                </div>
              </div>

              <div class="mt-4 flex flex-wrap items-center gap-2">
                <a href="{{ route('conveyances.show', $conveyance) }}" class="inline-flex items-center gap-2 rounded-md border border-indigo-100 bg-white px-3 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50">View</a>
                <a href="{{ route('conveyances.edit', $conveyance) }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                <a href="{{ route('conveyances.show', $conveyance) }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-1 text-xs font-medium text-white hover:bg-indigo-700">PDF</a>
              </div>
            </article>
          @endforeach
        </section>
      @endif
    </div>
  </body>
</html>
