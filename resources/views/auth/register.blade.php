<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#e0e0ff",
                        "error": "#ba1a1a",
                        "on-primary-fixed": "#00105b",
                        "on-background": "#191c1d",
                        "inverse-primary": "#bac3ff",
                        "error-container": "#ffdad6",
                        "on-surface": "#191c1d",
                        "primary-container": "#4453a7",
                        "tertiary-fixed": "#ebdcff",
                        "surface": "#f8f9fa",
                        "on-tertiary-container": "#dec9ff",
                        "surface-variant": "#e1e3e4",
                        "on-error-container": "#93000a",
                        "tertiary-fixed-dim": "#d4bbff",
                        "primary": "#2b3b8e",
                        "on-secondary-container": "#27308a",
                        "on-tertiary-fixed": "#260058",
                        "primary-fixed": "#dee0ff",
                        "on-error": "#ffffff",
                        "surface-container-high": "#e7e8e9",
                        "on-secondary-fixed-variant": "#343d96",
                        "surface-tint": "#4858ab",
                        "surface-bright": "#f8f9fa",
                        "inverse-surface": "#2e3132",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#d9dadb",
                        "secondary": "#4c56af",
                        "secondary-fixed-dim": "#bdc2ff",
                        "on-secondary": "#ffffff",
                        "on-primary-container": "#c9cfff",
                        "background": "#f8f9fa",
                        "outline": "#757684",
                        "on-tertiary": "#ffffff",
                        "on-surface-variant": "#454652",
                        "on-primary-fixed-variant": "#2f3f92",
                        "on-tertiary-fixed-variant": "#572e99",
                        "surface-container-highest": "#e1e3e4",
                        "primary-fixed-dim": "#bac3ff",
                        "inverse-on-surface": "#f0f1f2",
                        "surface-container-low": "#f3f4f5",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c5c5d4",
                        "tertiary": "#532a95",
                        "on-secondary-fixed": "#000767",
                        "secondary-container": "#959efd",
                        "tertiary-container": "#6b44ae",
                        "surface-container": "#edeeef"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">


    <main class="flex-grow flex items-center justify-center pt-16 px-4">
        <div class="max-w-xl w-full overflow-hidden rounded-xl bg-surface-container-lowest shadow-2xl shadow-on-surface/10 border border-outline-variant/20">
            <div class="p-8 md:p-12">
                <div class="mb-10 text-center">
                    <h1 class="text-3xl font-extrabold text-on-surface font-headline tracking-tight mb-2">Create Account</h1>
                    <p class="text-on-surface-variant font-body mb-4">Set up your portal access to start managing conveyance records.</p>
                    
                    <p class="text-xs text-secondary font-medium">
                        Only Gmail addresses are allowed. After registering, wait for admin approval before you can log in.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-500 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="hidden">
                        <input type="text" name="company" tabindex="-1" autocomplete="off"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            Full Name
                        </label>
                        <input class="w-full px-0 py-3 bg-transparent border-0 border-b-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-medium text-on-surface placeholder:text-outline-variant/60" 
                            placeholder="Johnathan Doe" type="text" name="name" value="{{ old('name') }}" required autofocus/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">mail</span>
                            Email Address
                        </label>
                        <input class="w-full px-0 py-3 bg-transparent border-0 border-b-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-medium text-on-surface placeholder:text-outline-variant/60" 
                            placeholder="j.doe@gmail.com" type="email" name="email" value="{{ old('email') }}" required/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">lock</span>
                                Password
                            </label>
                            <input class="w-full px-0 py-3 bg-transparent border-0 border-b-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-medium text-on-surface placeholder:text-outline-variant/60" 
                                placeholder="••••••••" type="password" name="password" required/>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">shield</span>
                                Confirm
                            </label>
                            <input class="w-full px-0 py-3 bg-transparent border-0 border-b-2 border-outline-variant focus:border-primary focus:ring-0 transition-all font-medium text-on-surface placeholder:text-outline-variant/60" 
                                placeholder="••••••••" type="password" name="password_confirmation" required/>
                        </div>
                    </div>
                    
                    <button class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2" type="submit">
                        Register Account
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-outline-variant/10 text-center">
                    <p class="text-on-surface-variant text-sm font-body">
                        Already have an account? 
                        <a class="text-primary font-bold ml-1 hover:underline" href="{{ route('login') }}">Sign in to Ledger</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>