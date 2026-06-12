<div>
    <div class="flex min-h-screen bg-gray-50 dark:bg-gray-950 font-sans" dir="rtl">
        
        <!-- Form Side -->
        <div class="flex w-full flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:w-1/2 lg:px-20 xl:px-24 bg-white dark:bg-gray-900 relative z-10 shadow-2xl">
            <div class="mx-auto w-full max-w-sm lg:max-w-md">
                
                <!-- Header -->
                <div class="mb-8 text-center sm:text-right">
                    <img src="{{ asset('images/app_logo.png') }}?v={{ time() }}" alt="EduBook" class="h-20 w-auto mx-auto sm:mx-0 mb-6 drop-shadow-sm transition-transform hover:scale-105">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white" style="font-family: 'Vazirmatn', sans-serif;">
                        چوونە ژوورەوە بۆ هەژمارەکەت
                    </h2>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        بەخێربێیتەوە! تکایە زانیارییەکانت بنووسە بۆ چوونە ناو داشبۆرد.
                    </p>
                </div>

                <!-- Form -->
                <div class="mt-8">
                    <x-filament-panels::form id="form" wire:submit="authenticate">
                        {{ $this->form }}

                        <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="$this->hasFullWidthFormActions()"
                            class="mt-8"
                        />
                    </x-filament-panels::form>
                </div>
                
                <!-- Footer -->
                <div class="mt-12 text-center sm:text-right">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium tracking-wide">
                        &copy; {{ date('Y') }} EduBook Platform. هەموو مافەکان پارێزراوە.
                    </p>
                </div>
            </div>
        </div>

        <!-- Showcase Side (Hidden on mobile) -->
        <div class="relative hidden w-0 flex-1 lg:block overflow-hidden bg-primary-900">
            <!-- Abstract CSS Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-bl from-primary-600 via-primary-800 to-gray-900">
                <!-- Grid pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')]"></div>
                
                <!-- Decorative blobs -->
                <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary-400/40 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-blob"></div>
                <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-blue-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-1/4 right-1/3 w-96 h-96 bg-purple-500/30 rounded-full mix-blend-overlay filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
            </div>
            
            <!-- Showcase Content -->
            <div class="absolute inset-0 flex items-center justify-center p-12">
                <div class="max-w-xl text-center backdrop-blur-md bg-white/5 p-12 rounded-[2.5rem] border border-white/10 shadow-2xl transition-transform hover:scale-105 duration-500">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 mb-8 shadow-inner border border-white/20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-black text-white mb-6 leading-tight drop-shadow-md" style="font-family: 'Vazirmatn', sans-serif;">
                        باشترین پلاتفۆرمی پەروەردەیی
                    </h1>
                    <p class="text-lg text-primary-100 font-medium leading-relaxed opacity-90">
                        بەڕێوەبردنی دامەزراوەکان، خوێندکاران و پرۆسەی پەروەردە بە ئاسانترین و مۆدێرنترین شێواز. هەموو شتێک لە یەک شوێندا.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        /* Premium button styling without breaking Filament core structure */
        button[type="submit"] {
            box-shadow: 0 4px 14px 0 rgba(var(--primary-600), 0.39);
            transition: all 0.3s ease;
        }
        button[type="submit"]:hover {
            box-shadow: 0 6px 20px rgba(var(--primary-600), 0.4);
            transform: translateY(-1px);
        }
    </style>
</div>
