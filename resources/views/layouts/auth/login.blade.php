<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - E-Report</title>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col sm:justify-center items-center p-4">
        
        <div class="w-full sm:max-w-md bg-white shadow-2xl overflow-hidden rounded-2xl border border-slate-100">
            <!-- Header Card (Judul di dalam Card) -->
            <div class="bg-slate-50 border-b border-slate-100 p-8 text-center">
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    E-Report <span class="text-primary">Admin</span>
                </h1>
                <p class="text-slate-500 text-sm mt-2 font-medium">Singapore Piaget Academy</p>
            </div>

            <div class="p-8">

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-600">Email</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="input input-bordered w-full focus:input-primary bg-slate-50" 
                               placeholder="admin@example.com"
                               required autofocus />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error font-medium">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control w-full mb-4">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-600">Password</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               class="input input-bordered w-full focus:input-primary bg-slate-50" 
                               placeholder="••••••••"
                               required autocomplete="current-password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mt-6">
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary" />
                            <span class="label-text text-slate-600 font-medium">Remember me</span>
                        </label>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="btn btn-primary w-full text-white font-bold tracking-wide shadow-lg shadow-blue-100">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 text-slate-400 text-xs font-medium tracking-widest uppercase">
            &copy; {{ date('Y') }} Aether Code
        </div>
    </div>
</body>
</html>
