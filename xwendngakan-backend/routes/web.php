<?php

use Illuminate\Http\Request;
use App\Models\InstitutionType;
use App\Models\Institution;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Root: redirect to portal
Route::get('/', function () {
    if (auth()->check()) return redirect()->route('portal.dashboard');
    return redirect()->route('portal.home');
})->name('home');

// Admin: approve/reject an institution straight from the list (plain GET link,
// no Livewire — guaranteed to work from the table action button).
Route::get('/admin/institutions/{institution}/toggle-approval', function (Institution $institution) {
    abort_unless(auth()->check() && auth()->user()->is_admin, 403);
    $institution->update(['approved' => ! $institution->approved]);
    // Go straight back to the admin institutions list (not back(), which can
    // fall back to "/" and bounce the admin into the portal).
    return redirect()->route('filament.admin.resources.institutions.index');
})->middleware('auth')->name('admin.institutions.toggle-approval');

// =====================
//  INSTITUTION PORTAL
// =====================
Route::prefix('portal')->name('portal.')->group(function () {

    // ---- Public ----
    Route::get('/', function () {
        if (auth()->check()) return redirect()->route('portal.dashboard');
        return view('portal.home');
    })->name('home');

    Route::get('/login', function () {
        if (auth()->check()) {
            // ئەگەر ئەدمین بوو بیبەرە بۆ پاناڵی ئەدمین
            if (auth()->user()->is_admin) {
                return redirect('/admin');
            }
            if (auth()->user()->is_approved) {
                return redirect()->route('portal.dashboard');
            }
            return redirect()->route('portal.waiting');
        }
        return view('portal.auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // ئەگەر ئەدمین بوو بیبەرە بۆ پاناڵی ئەدمین
            if (Auth::user()->is_admin) {
                // هاوکات لە گارڈی ئەدمینەوە لۆگین بکە تاکو دووجار لۆگین پێویست نەبێت
                Auth::guard('admin')->login(Auth::user(), $request->boolean('remember'));
                return redirect('/admin');
            }
            return redirect()->route('portal.dashboard');
        }
        return back()->withErrors(['email' => 'ئیمەیڵ یان وشەی نهێنی هەڵەیە.'])->withInput();
    })->name('login.submit');

    Route::get('/register', function () {
        if (auth()->check()) return redirect()->route('portal.dashboard');
        return view('portal.auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'is_approved' => true,
            'user_type'   => 'portal',
        ]);
        Auth::login($user);
        return redirect()->route('portal.dashboard');
    })->name('register.submit');

    Route::get('/waiting-approval', function () {
        if (!auth()->check()) return redirect()->route('portal.login');
        // ئەگەر ئەدمین بوو بیبەرە بۆ پاناڵی ئەدمین
        if (auth()->user()->is_admin) return redirect('/admin');
        if (auth()->user()->is_approved) return redirect()->route('portal.dashboard');
        return view('portal.auth.waiting');
    })->name('waiting');

    Route::post('/logout', function (Request $request) {
        Auth::guard('admin')->logout(); // هاوکات لە گارڈی ئەدمینیشەوە دەرچوون
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.home');
    })->name('logout');

    // ---- Protected ----
    Route::middleware(['auth', 'approved', 'redirect_admin'])->group(function () {

        Route::get('/dashboard', function () {
            $user = auth()->user();
            $institution = Institution::where('user_id', $user->id)
                ->orderByDesc('approved')
                ->first();
            $posts = $institution
                ? Post::where('institution_id', $institution->id)->latest()->paginate(10)
                : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $types = InstitutionType::active()->ordered()->get();
            // Map of type key → academic flags for JavaScript
            $typeFlags = $types->keyBy('key')->map(fn($t) => [
                'has_colleges'    => (bool) $t->has_colleges,
                'has_departments' => (bool) $t->has_departments,
            ])->toArray();
            return view('portal.dashboard', compact('institution', 'posts', 'types', 'typeFlags'));
        })->name('dashboard');

        Route::post('/institution/save', function (Request $request) {
            $user = auth()->user();
            // Always prefer the approved institution to avoid orphan duplicates
            $linkedInstitution = Institution::where('user_id', $user->id)
                ->orderByDesc('approved')
                ->first();
            $data = $request->validate([
                'nku'      => 'required|string|max:255',
                'nar'      => 'nullable|string|max:255',
                'nen'      => 'nullable|string|max:255',
                'type'     => 'required|string',
                'country'  => 'required|string|max:255',
                'city'     => 'required|string|max:255',
                'phone'    => 'nullable|string|max:20',
                'email'    => 'nullable|email|max:255',
                'addr'     => 'nullable|string|max:500',
                'lat'      => 'nullable|numeric',
                'lng'      => 'nullable|numeric',
                'video'    => 'nullable|url|max:500',
                'desc'     => 'nullable|string',
                'desc_en'  => 'nullable|string',
                'desc_ar'  => 'nullable|string',
                'web'      => 'nullable|url|max:255',
                'clg'                    => 'nullable|array',
                'clg.*.name'             => 'nullable|string|max:255',
                'clg.*.fee'              => 'nullable|string|max:50',
                'clg.*.discount'         => 'nullable|string|max:20',
                'clg.*.depts'            => 'nullable|array',
                'clg.*.depts.*.name'     => 'nullable|string|max:255',
                'clg.*.depts.*.fee'      => 'nullable|string|max:50',
                'clg.*.depts.*.discount' => 'nullable|string|max:20',
                'simple_dept'            => 'nullable|array',
                'simple_dept.*'          => 'nullable|string|max:255',
                'simple_fee'             => 'nullable|array',
                'simple_discount'        => 'nullable|array',
                'fb'               => 'nullable|string|max:255',
                'ig'       => 'nullable|string|max:255',
                'tg'       => 'nullable|string|max:255',
                'wa'       => 'nullable|string|max:50',
                'tk'       => 'nullable|string|max:255',
                'yt'       => 'nullable|string|max:255',
                'founded_year'     => 'nullable|integer',
                'students_count'   => 'nullable|integer',
                'img'      => 'nullable|file|extensions:jpg,jpeg,png,gif,webp|max:10240',
                'logo'     => 'nullable|file|extensions:jpg,jpeg,png,gif,webp|max:10240',
            ]);

            // Handle image uploads — only update if a new file is provided
            unset($data['img'], $data['logo']);
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $file = $request->file('img');
                $filename = md5(uniqid(rand(), true)) . '.' . strtolower($file->getClientOriginalExtension());
                $path = $file->storeAs('institutions', $filename, 'public');
                if ($path) $data['img'] = '/storage/' . $path;
            }
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $file = $request->file('logo');
                $filename = md5(uniqid(rand(), true)) . '.' . strtolower($file->getClientOriginalExtension());
                $path = $file->storeAs('institutions/logos', $filename, 'public');
                if ($path) $data['logo'] = '/storage/' . $path;
            }

            // Build unified colleges JSON + tuition_plans from new nested form
            $clgInput    = $data['clg'] ?? [];
            $simpleDepts = $data['simple_dept'] ?? [];
            $simpleFees  = $data['simple_fee'] ?? [];
            $simpleDiscs = $data['simple_discount'] ?? [];
            unset($data['clg'], $data['simple_dept'], $data['simple_fee'], $data['simple_discount']);

            $collegesJson = [];
            $tuitionPlans = [];
            $allDeptNames = [];

            $typeObj = InstitutionType::where('key', $data['type'])->first();
            $hasColleges = $typeObj ? (bool)$typeObj->has_colleges : false;

            if ($hasColleges && !empty($clgInput)) {
                // Mode 1: has colleges (universities, institutes)
                foreach (array_values($clgInput) as $col) {
                    $colName = trim((string)($col['name'] ?? ''));
                    if (!$colName) continue;
                    $depts = [];
                    foreach (array_values($col['depts'] ?? []) as $dept) {
                        $dn = trim((string)($dept['name'] ?? ''));
                        if (!$dn) continue;
                        $fee  = trim((string)($dept['fee'] ?? ''));
                        $disc = trim((string)($dept['discount'] ?? ''));
                        $depts[]        = ['name' => $dn, 'fee' => $fee, 'discount' => $disc];
                        $allDeptNames[] = $dn;
                        $tuitionPlans[] = ['dept' => $dn, 'fee' => $fee, 'discount' => $disc];
                    }
                    $collegesJson[] = [
                        'name'     => $colName,
                        'fee'      => trim((string)($col['fee'] ?? '')),
                        'discount' => trim((string)($col['discount'] ?? '')),
                        'depts'    => $depts,
                    ];
                }
                $data['colleges'] = json_encode($collegesJson, JSON_UNESCAPED_UNICODE);
                $data['depts']    = implode("\n", $allDeptNames);
            } else {
                // Mode 2: simple depts (schools etc.)
                foreach ($simpleDepts as $i => $dn) {
                    $dn = trim((string)$dn);
                    if (!$dn) continue;
                    $allDeptNames[] = $dn;
                    $tuitionPlans[] = [
                        'dept'     => $dn,
                        'fee'      => trim((string)($simpleFees[$i] ?? '')),
                        'discount' => trim((string)($simpleDiscs[$i] ?? '')),
                    ];
                }
                $data['colleges'] = '';
                $data['depts']    = implode("\n", $allDeptNames);
            }
            // Pass array directly — model cast handles json encoding
            $data['tuition_plans'] = $tuitionPlans;

            if ($linkedInstitution) {
                $linkedInstitution->update($data);
                $msg = 'زانیارییەکانی دامەزراوەکەت بە سەرکەوتوویی نوێکرانەوە.';
            } else {
                $data['user_id']  = $user->id;
                $data['approved'] = false;
                Institution::create($data);
                $msg = 'زانیارییەکانت تۆمارکرا، تکایە چاوەڕێبە تا لەلایەن ئەدمینەوە پەسەند دەکرێت.';
            }
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return back()->with('success', $msg);
        })->name('institution.save');

        Route::post('/posts/store', function (Request $request) {
            $user = auth()->user();
            $institution = Institution::where('user_id', $user->id)
                ->orderByDesc('approved')
                ->first();
            if (!$institution) return back()->with('error', 'پێشتر دامەزراوەکەت تۆمار بکە.');
            if (!$institution->approved) return back()->with('error', 'دامەزراوەکەت هێشتا قبوڵ نەکراوە. پاش قبوڵکردنی ئەدمین دەتوانیت پۆست بکەیت.');

            $data = $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'image'   => 'nullable|file|extensions:jpg,jpeg,png,gif,webp|max:4096',
            ]);

            $post = new Post();
            $post->institution_id = $institution->id;
            $post->title          = $data['title'];
            $post->content        = $data['content'];
            $post->approved       = true;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = md5(uniqid(rand(), true)) . '.' . strtolower($file->getClientOriginalExtension());
                $path = $file->storeAs('posts', $filename, 'public');
                $post->image = '/storage/' . $path;
            }
            $post->save();
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'پۆستەکەت بە سەرکەوتوویی بڵاوکرایەوە.']);
            }
            return back()->with('success', 'پۆستەکەت بە سەرکەوتوویی بڵاوکرایەوە.');
        })->name('posts.store');

        Route::post('/settings/save', function (Request $request) {
            $user = auth()->user();
            $data = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
            ]);
            $update = ['name' => $data['name'], 'email' => $data['email']];
            if (!empty($data['password'])) $update['password'] = Hash::make($data['password']);
            $user->update($update);
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'زانیارییەکان بە سەرکەوتوویی نوێکرانەوە.']);
            }
            return back()->with('success', 'زانیارییەکان بە سەرکەوتوویی نوێکرانەوە.');
        })->name('settings.save');

        Route::delete('/posts/{id}', function ($id) {
            $user = auth()->user();
            $institution = Institution::where('user_id', $user->id)
                ->orderByDesc('approved')
                ->first();
            if (!$institution) abort(403);
            $post = Post::where('id', $id)->where('institution_id', $institution->id)->firstOrFail();
            $post->delete();
            return back()->with('success', 'پۆستەکە سڕایەوە.');
        })->name('posts.delete');
    });
});
