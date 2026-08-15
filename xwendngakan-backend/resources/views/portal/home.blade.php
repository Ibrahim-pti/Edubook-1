@extends('portal.layout')
@section('title', 'EduBook - IQ — پلاتفۆرمی پەروەردەیی کوردستان')

@section('styles')
<style>
/* ════════════ MODERN FLAT UI & ANIMATIONS ════════════ */
:root {
    --gold-solid: #fbbf24;
    --gold-hover: #f59e0b;
    --border-light: rgba(255, 255, 255, 0.1);
    --border-strong: rgba(255, 255, 255, 0.2);
    --bg-dark: #080c14;
    --bg-card: #0f172a;
}

body {
    background-color: var(--bg-dark);
    background-image: 
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
}

@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.reveal { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }
.delay-6 { animation-delay: 0.6s; }

/* ════════════ HERO SECTION ════════════ */
.hero {
    position: relative;
    padding: 10rem 1.5rem 6rem;
    text-align: center;
    border-bottom: 1px solid var(--border-light);
}

.badge {
    display: inline-block;
    padding: 6px 16px; border-radius: 4px;
    background: transparent;
    border: 1px solid var(--gold-solid);
    color: var(--gold-solid); font-size: 0.9rem; font-weight: 800;
    margin-bottom: 2rem;
}

.hero-title {
    font-size: clamp(2.2rem, 5vw, 4rem);
    font-weight: 900; line-height: 1.3;
    margin-bottom: 1.5rem; color: #fff;
}

.hero-desc {
    font-size: 1.25rem;
    color: var(--txt2); max-width: 600px;
    margin: 0 auto 3rem; line-height: 1.8;
}

.btn-flat {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 32px; border-radius: 4px;
    font-weight: 800; font-size: 1.1rem;
    text-decoration: none; transition: all 0.2s;
}
.btn-primary-flat { background: var(--gold-solid); color: #000; border: 1px solid var(--gold-solid); }
.btn-primary-flat:hover { background: var(--gold-hover); border-color: var(--gold-hover); transform: translateY(-2px); }
.btn-outline-flat { background: transparent; color: #fff; border: 1px solid var(--border-strong); }
.btn-outline-flat:hover { background: var(--border-light); transform: translateY(-2px); }

/* ════════════ TYPOGRAPHY & READABILITY ════════════ */
.section { padding: 6rem 1.5rem; border-bottom: 1px solid var(--border-light); }
.container { max-width: 1000px; margin: 0 auto; }

.sec-head { margin-bottom: 4rem; text-align: center; }
.sec-title { font-size: 2.2rem; font-weight: 900; color: #fff; margin-bottom: 1rem; }
.sec-subtitle { color: var(--gold-solid); font-weight: 800; font-size: 1.1rem; text-transform: uppercase; }

/* ════════════ ABOUT / GOALS ════════════ */
.goals-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;
}
@media (max-width: 768px) { .goals-grid { grid-template-columns: 1fr; } }

.goal-card {
    background: var(--bg-card);
    border: 1px solid var(--border-strong);
    padding: 2.5rem; border-radius: 8px;
}
.goal-icon {
    width: 60px; height: 60px; border-radius: 4px;
    background: transparent; border: 1px solid var(--gold-solid);
    color: var(--gold-solid); font-size: 2rem;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.5rem;
}
.goal-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 1rem; }
.goal-desc { font-size: 1.15rem; color: var(--txt2); line-height: 1.8; }

/* ════════════ HOW TO REGISTER ════════════ */
.step-row {
    display: flex; gap: 2rem; align-items: flex-start;
    padding: 2rem; background: var(--bg-card);
    border: 1px solid var(--border-strong);
    border-radius: 8px; margin-bottom: 1.5rem;
}
@media (max-width: 640px) { .step-row { flex-direction: column; } }
.step-num {
    flex-shrink: 0; width: 50px; height: 50px; border-radius: 4px;
    background: var(--gold-solid); color: #000;
    font-size: 1.5rem; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
}
.step-content h3 { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; }
.step-content p { font-size: 1.15rem; color: var(--txt2); line-height: 1.8; margin: 0; }
.step-highlight { color: #fff; font-weight: 700; }

/* ════════════ APP DOWNLOAD BUTTONS ════════════ */
.app-download-btn {
    display: inline-flex; align-items: center; gap: 14px;
    background: #0f172a;
    border: 1px solid var(--border-strong); border-radius: 14px;
    padding: 10px 24px; color: #fff; text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.app-download-btn:hover {
    background: #1e293b; border-color: var(--gold-solid);
    transform: translateY(-3px);
}
.app-download-btn svg { width: 32px; height: 32px; fill: currentColor; }
.app-download-text { display: flex; flex-direction: column; align-items: flex-start; }
.app-download-text .sub { font-size: 0.75rem; color: var(--txt2); text-transform: uppercase; letter-spacing: 1px; line-height: 1.2; }
.app-download-text .title { font-size: 1.3rem; font-weight: 900; line-height: 1.2; }

/* ════════════ APP PREVIEW ════════════ */
.app-preview-section {
    display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center;
}
@media (max-width: 768px) { .app-preview-section { grid-template-columns: 1fr; } }
.app-text h2 { font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 1.5rem; }
.app-text p { font-size: 1.15rem; color: var(--txt2); line-height: 1.8; margin-bottom: 1.5rem; }
.app-features { list-style: none; padding: 0; }
.app-features li {
    font-size: 1.15rem; color: #fff; margin-bottom: 1rem;
    display: flex; align-items: center; gap: 10px; font-weight: 600;
}
.app-features li::before {
    content: '✓'; color: var(--gold-solid); font-weight: 900;
}
.app-mockup {
    width: 100%; max-width: 300px; margin: 0 auto;
    border: 4px solid var(--border-strong); border-radius: 30px;
    background: #000; overflow: hidden;
    height: 550px; display: flex; flex-direction: column;
}
.app-mockup-header { height: 60px; background: #1e293b; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; }
.app-mockup-body { flex: 1; padding: 1.5rem; }
.app-mockup-card { background: #1e293b; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border: 1px solid var(--border-light); }
.app-mockup-img { width: 100%; height: 100px; background: rgba(251,191,36,0.2); border-radius: 4px; margin-bottom: 1rem; }
.app-mockup-line { height: 12px; background: rgba(255,255,255,0.1); border-radius: 4px; margin-bottom: 0.5rem; }
.app-mockup-line.short { width: 60%; }

/* CTA */
.cta-banner { text-align: center; padding: 4rem 2rem; background: var(--bg-card); border: 1px solid var(--border-strong); border-radius: 8px; margin-top: 4rem; }
.cta-banner h2 { font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 1.5rem; }
</style>
@endsection

@section('content')

{{-- ═══════ HERO ═══════ --}}
<section class="hero">
    <div class="container">
        <div class="badge reveal">تایبەت بە ناوەندەکانی خوێندن</div>
        <h1 class="hero-title reveal delay-1">
            دامەزراوەکەت بناسێنە<br>
            بە خوێندکاران
        </h1>
        <p class="hero-desc reveal delay-2">
            پلاتفۆرمی EduBook - IQ ژینگەیەکی دیجیتاڵییە؛ زانکۆ، پەیمانگا و قوتابخانەکان ڕاستەوخۆ دەبەستێتەوە بە خوێندکارانەوە.
        </p>
        <div class="reveal delay-3" style="display:flex; gap:1rem; justify-content:center;">
            <a href="{{ route('portal.register') }}" class="btn-flat btn-primary-flat">ئێستا خۆت تۆمار بکە</a>
            <a href="#about" class="btn-flat btn-outline-flat">زیاتر بزانە</a>
        </div>
    </div>
</section>

{{-- ═══════ 1. WHAT IS IT & GOALS ═══════ --}}
<section class="section" id="about">
    <div class="container">
        <div class="sec-head reveal">
            <div class="sec-subtitle">ڕێبەری سەرەتایی</div>
            <h2 class="sec-title">ئەم وێبسایتە چییە و ئامانجی چییە؟</h2>
        </div>
        
        <div class="goals-grid">
            <div class="goal-card reveal delay-1">
                <div class="goal-icon">🌐</div>
                <h3 class="goal-title">وێبسایتەکە چییە؟</h3>
                <p class="goal-desc">
                    ئەم وێبسایتە (کە بە پۆرتاڵ ناودەبرێت) تایبەتە بە خاوەن دامەزراوە پەروەردەییەکان. لێرەدا دەتوانیت هەژمارێک بۆ قوتابخانە، پەیمانگا یان زانکۆکەت بکەیتەوە، و هەموو زانیارییەکانی وەک ناونیشان، جۆر و وێنەکانت دابنێیت بۆ ئەوەی بە فەرمی بناسێندرێیت.
                </p>
            </div>
            
            <div class="goal-card reveal delay-2">
                <div class="goal-icon">🎯</div>
                <h3 class="goal-title">ئامانجی سەرەکی</h3>
                <p class="goal-desc">
                    ئامانجمان ئەوەیە هەموو دامەزراوە پەروەردەییەکانی کوردستان لە یەک ئەپلیکەیشنی مۆبایلدا کۆبکەینەوە. کاتێک تۆ لێرە خۆت تۆمار دەکەیت، دامەزراوەکەت لە ئەپلیکەیشنی EduBook - IQ دەردەکەوێت و خوێندکاران دەتوانن پرۆفایل و هەواڵەکانت ببینن.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════ 2. HOW TO REGISTER ═══════ --}}
<section class="section">
    <div class="container">
        <div class="sec-head reveal">
            <div class="sec-subtitle">هەنگاوەکانی کارکردن</div>
            <h2 class="sec-title">چۆنیەتی خۆ تۆمارکردن و بەشداریکردن</h2>
        </div>

        <div class="step-row reveal delay-1">
            <div class="step-num">١</div>
            <div class="step-content">
                <h3>دروستکردنی هەژمار و پڕکردنەوەی فۆڕم</h3>
                <p>سەرەتا بە ئیمەیڵێک هەژمارێک دروست دەکەیت، پاشان فۆڕمێکی تایبەت پڕدەکەیتەوە کە تێیدا <span class="step-highlight">ناوی دامەزراوەکەت، جۆرەکەی، ناونیشان و ژمارەی پەیوەندی</span> دەنووسیت. دڵنیابە لەوەی زانیارییەکانت بە دروستی داخڵ دەکەیت.</p>
            </div>
        </div>

        <div class="step-row reveal delay-2">
            <div class="step-num">٢</div>
            <div class="step-content">
                <h3>پەسەندکردن لەلایەن ئەدمینەوە</h3>
                <p>دوای ناردنی فۆڕمەکە، هەژمارەکەت دەچێتە حاڵەتی چاوەڕوانی (Waiting). تیمی ئێمە زانیارییەکانت دەپشکنێت بۆ دڵنیابوون لە ڕاستی دامەزراوەکە، و دواتر هەژمارەکەت کارا (Approve) دەکرێت.</p>
            </div>
        </div>

        <div class="step-row reveal delay-3">
            <div class="step-num">٣</div>
            <div class="step-content">
                <h3>بڵاوکردنەوەی هەواڵ و بابەتەکان</h3>
                <p>پاش پەسەندکردن، ڕاستەوخۆ دەتوانیت بچیتە ناو داشبۆردەکەت. لەوێوە دەتوانیت دەست بکەیت بە بڵاوکردنەوەی پۆست، هەواڵی نوێ، یان کاتی وەرگرتنی خوێندکاران.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════ APP FEATURES ═══════ --}}
<section class="section" id="app-features">
    <div class="container">
        <div class="sec-head reveal">
            <div class="sec-subtitle">تایبەتمەندییەکان</div>
            <h2 class="sec-title">مواسەفاتەکانی ئەپلیکەیشنی مۆبایل</h2>
        </div>
        
        <div class="goals-grid">
            <div class="goal-card reveal delay-1">
                <div class="goal-icon">🔍</div>
                <h3 class="goal-title">گەڕانی زیرەک و فلتەرکردن</h3>
                <p class="goal-desc">
                    سیستەمێکی پێشکەوتووی فلتەرکردن و گەڕان لەناو ئەپلیکەیشنەکەدا هەیە. خوێندکاران دەتوانن بە زووترین کات، بەپێی شار، قەزا، جۆری خوێندن (حکومی یان تایبەت) و ئاستی قۆناغەکان بەدوای ناوەندەکانی خوێندندا بگەڕێن و ڕاستەوخۆ داتاکان بپاڵێون.
                </p>
            </div>
            
            <div class="goal-card reveal delay-2">
                <div class="goal-icon">📍</div>
                <h3 class="goal-title">نەخشە و ئاڕاستەکان</h3>
                <p class="goal-desc">
                    ئەپلیکەیشنەکە بە تەواوی بەستراوەتەوە بە نەخشەوە. خوێندکاران دەتوانن بە وردی شوێنی جوگرافی دامەزراوەکەت ببینن، دووری نێوان خۆیان و زانکۆ یان پەیمانگاکە بزانن، و ڕاستەوخۆ ئاڕاستەی چوون (Directions) بەدەست بهێنن بۆ ئاسانکاری لە کاتی سەردانیکردندا.
                </p>
            </div>

            <div class="goal-card reveal delay-3">
                <div class="goal-icon">🔔</div>
                <h3 class="goal-title">هەواڵ و ئاگانامە (Notifications)</h3>
                <p class="goal-desc">
                    لێرە هەر کاتێک هەواڵێک، ڕاگەیاندنێکی گرنگ، یان کاتی دەستپێکردنی وەرگرتنی خوێندکاران بڵاودەکەیتەوە، پۆستەکەت ڕاستەوخۆ وەک نۆتیفیکەیشن دەگاتە سەر شاشەی مۆبایلی هەموو ئەو خوێندکارانەی کە ئەپەکە بەکاردەهێنن.
                </p>
            </div>

            <div class="goal-card reveal delay-4">
                <div class="goal-icon">📱</div>
                <h3 class="goal-title">پڕۆفایلی تەواو و گشتگیر</h3>
                <p class="goal-desc">
                    لاپەڕەیەکی تایبەت و پرۆفیشناڵ کە وەک وێبسایتێکی مینی (Mini) کاردەکات بۆ دامەزراوەکەت. تێیدا لۆگۆکەت، وێنەکان، لینکی سۆشیاڵ میدیاکانت، و ژمارەی ڕاستەوخۆی پەیوەندیکردن (کە تەنها بە یەک کلیک پەیوەندیت پێوە دەکرێت) دەخرێنە ڕوو.
                </p>
            </div>

            <div class="goal-card reveal delay-5">
                <div class="goal-icon">📄</div>
                <h3 class="goal-title">دروستکردنی سیڤی (CV)</h3>
                <p class="goal-desc">
                    خوێندکاران دەتوانن بە ئاسانی لەناو ئەپلیکەیشنەکەدا سیڤی (CV) پرۆفیشناڵی تایبەت بە خۆیان دروست بکەن و بڵاوی بکەنەوە بۆ بەدەستهێنانی هەلی کار یان پێشکەشکردن بۆ زانکۆکان.
                </p>
            </div>

            <div class="goal-card reveal delay-6">
                <div class="goal-icon">👨‍🏫</div>
                <h3 class="goal-title">وانەی تایبەت بۆ مامۆستایان</h3>
                <p class="goal-desc">
                    مامۆستایان دەتوانن هەژماری تایبەت بە خۆیان بکەنەوە و ڕیکلام بۆ وانە تایبەتەکان (Private Lessons) یان خولەکانیان بکەن. خوێندکارانیش بە ئاسانی دەیاندۆزنەوە و پەیوەندییان پێوە دەکەن.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════ 3. HOW IT LOOKS IN APP ═══════ --}}
<section class="section">
    <div class="container">
        <div class="app-preview-section">
            <div class="app-text reveal delay-1">
                <div class="sec-subtitle" style="margin-bottom: 1rem;">ئەنجامەکان</div>
                <h2>نیشاندان لە ناو ئەپلیکەیشنی خوێندکاران</h2>
                <p>کاتێک تۆ زانیارییەکانت لێرە (لە پۆرتاڵ) داخڵ دەکەیت و پەسەند دەکرێیت، دامەزراوەکەت ڕاستەوخۆ دەگوازرێتەوە ناو ئەپلیکەیشنی مۆبایلی EduBook - IQ.</p>
                <p>خوێندکاران لە ناو ئەپلیکەیشنەکە بەم شێوەیە دەتبینن:</p>
                <ul class="app-features">
                    <li>پڕۆفایلێکی تایبەت بە خۆت بە لۆگۆکەتەوە دەردەکەوێت.</li>
                    <li>دەتوانن ڕاستەوخۆ ژمارەی پەیوەندیت ببینن و پەیوەندیت پێوە بکەن.</li>
                    <li>دەتوانن ناونیشانەکەت لەسەر نەخشە ببینن بۆ ئاسانکاری سەردانیکردن.</li>
                    <li>هەر پۆستێک بڵاوی بکەیتەوە، وەکو (هەواڵ) دەردەکەوێت لە شاشەی سەرەکی خوێندکاران.</li>
                </ul>
                
                <div style="display: flex; gap: 1rem; margin-top: 2.5rem; flex-wrap: wrap;">
                    <a href="#" class="app-download-btn">
                        <svg viewBox="0 0 512 512"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
                        <div class="app-download-text">
                            <span class="sub">داونلۆد لە</span>
                            <span class="title">Google Play</span>
                        </div>
                    </a>
                    <a href="#" class="app-download-btn">
                        <svg viewBox="0 0 384 512"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.1-44.6-35.9-2.8-74.3 22.7-93.1 22.7-18.9 0-46.3-21-76.4-21C64.6 141 22.1 183.3 5 259.9c-29.2 130.6 62.4 252.3 118 252.3 27.6 0 39.5-16.7 72-16.7 32.2 0 42.6 16.5 72.3 16.5 56.5 0 119.5-103.5 142.4-192.1-49.9-22.1-91.1-71.1-91-151.2zm-97.6-184C236.9 44.5 250.7 14 240.2 0c-35.5 1.5-68.4 20-88.7 48.9-18.8 26.6-32.9 61.6-21.7 94 37.1 2.8 69.8-19.1 86.9-49.8h4.4z"/></svg>
                        <div class="app-download-text">
                            <span class="sub">داونلۆد لە</span>
                            <span class="title">App Store</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="app-mockup reveal delay-2">
                <div class="app-mockup-header"></div>
                <div class="app-mockup-body">
                    <div class="app-mockup-card">
                        <div class="app-mockup-img"></div>
                        <div class="app-mockup-line"></div>
                        <div class="app-mockup-line short"></div>
                    </div>
                    <div class="app-mockup-card">
                        <div style="display:flex; gap:10px; align-items:center; margin-bottom:1rem;">
                            <div style="width:40px; height:40px; background:var(--gold-solid); border-radius:50%;"></div>
                            <div>
                                <div class="app-mockup-line" style="width:100px; margin-bottom:5px;"></div>
                                <div class="app-mockup-line" style="width:60px; opacity:0.5;"></div>
                            </div>
                        </div>
                        <div class="app-mockup-line"></div>
                        <div class="app-mockup-line"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cta-banner reveal delay-3">
            <h2>ئامادەیت بۆ دەستپێکردن؟</h2>
            <p style="color:var(--txt2); font-size:1.15rem; margin-bottom:2rem;">یەکەم هەنگاو بنێ و دامەزراوەکەت بهێنە ناو دنیای دیجیتاڵی.</p>
            <a href="{{ route('portal.register') }}" class="btn-flat btn-primary-flat">دروستکردنی هەژمار</a>
        </div>
    </div>
</section>

{{-- ═══════ FOOTER ═══════ --}}
<footer style="text-align:center; padding:3rem 1.5rem; background:var(--bg-card); border-top:1px solid var(--border-strong);">
    <p style="color:var(--txt2); font-size:1.1rem;">© {{ date('Y') }} <strong style="color:var(--gold-solid)">EduBook - IQ</strong>. هەموو مافەکان پارێزراون.</p>
</footer>

@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});
</script>
@endsection
