
<div>
    <div class="min-h-screen flex items-center justify-center relative bg-slate-900 overflow-hidden" dir="rtl">
        <!-- Animated Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-[30%] -right-[10%] w-[60%] h-[60%] rounded-full bg-primary-600/20 blur-[120px] animate-pulse" style="animation-duration: 4s;"></div>
            <div class="absolute -bottom-[30%] -left-[10%] w-[60%] h-[60%] rounded-full bg-blue-600/20 blur-[120px] animate-pulse" style="animation-duration: 5s; animation-delay: 2s;"></div>
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)] z-0"></div>
        </div>

        <div class="relative z-10 w-full max-w-md px-6 py-12">
            <!-- Glassmorphism Card -->
            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/10 p-8 sm:p-10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] transform transition-all hover:scale-[1.01] duration-300">
                
                <!-- Logo & Header -->
                <div class="flex flex-col items-center justify-center mb-8">
                    <div class="relative mb-4 group">
                        <div class="absolute inset-0 bg-primary-500 rounded-2xl blur-xl opacity-50 group-hover:opacity-70 transition-opacity duration-300 animate-pulse"></div>
                        <img src="{{ asset('images/app_logo.png') }}?v={{ time() }}" 
                             class="relative h-24 w-24 object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-3" 
                             alt="EduBook Logo">
                    </div>
                    
                    <h2 class="text-3xl font-black text-center text-white mb-2 tracking-tight" style="font-family: 'Vazirmatn', sans-serif;">
                        بەخێربێیتەوە
                    </h2>
                    <p class="text-center text-slate-400 text-sm font-medium">
                        تکایە پێکهاتەکانی چوونە ژوورەوە پڕبکەرەوە
                    </p>
                </div>

                <!-- Form Section -->
                <div class="login-form-wrapper">
                    <x-filament-panels::form id="form" wire:submit="authenticate">
                        {{ $this->form }}

                        <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="$this->hasFullWidthFormActions()"
                            class="mt-6"
                        />
                    </x-filament-panels::form>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8 text-slate-500 text-xs font-medium tracking-wide">
                &copy; {{ date('Y') }} EduBook Platform. هەموو مافەکان پارێزراوە.
            </div>
        </div>
    </div>

    <!-- Custom CSS to override specific filament inputs if needed -->
    <style>
        .login-form-wrapper .fi-input-wrapper {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }
        .login-form-wrapper .fi-input-wrapper:focus-within {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary-500) !important;
            box-shadow: 0 0 0 1px var(--primary-500) !important;
        }
        .login-form-wrapper button[type="submit"] {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
            border: none;
            box-shadow: 0 4px 15px -3px rgba(var(--primary-600), 0.4);
            transition: all 0.3s ease;
        }
        .login-form-wrapper button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(var(--primary-600), 0.5);
        }
    </style>
</div>

