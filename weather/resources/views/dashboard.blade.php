<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

        :root {
            --glass: rgba(255,255,255,0.04);
            --glass-border: rgba(255,255,255,0.08);
            --glass-hover: rgba(255,255,255,0.08);
            --text-primary: #f0f0f0;
            --text-muted: rgba(240,240,240,0.45);
            --accent: #7eb8f7;
            --accent-warm: #f5a97f;
            --surface: #0d0f14;
            --surface-2: #13161d;
        }

        body, .min-h-screen, [class*="bg-gray"] {
            background-color: var(--surface) !important;
            color: var(--text-primary) !important;
            font-family: 'Syne', sans-serif !important;
        }

        .wx-wrap {
            min-height: 100vh;
            background:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(94, 140, 220, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(180, 100, 80, 0.08) 0%, transparent 55%),
                var(--surface);
            padding: 2rem 1.5rem 4rem;
        }

        /* --- Search --- */
        .wx-search-wrap {
            max-width: 520px;
            margin: 0 auto 3rem;
            position: relative;
        }
        .wx-search-input {
            width: 100%;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 14px 52px 14px 20px;
            color: var(--text-primary);
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            backdrop-filter: blur(12px);
        }
        .wx-search-input::placeholder { color: var(--text-muted); }
        .wx-search-input:focus {
            border-color: rgba(126,184,247,0.4);
            background: rgba(255,255,255,0.07);
        }
        .wx-search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 6px;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }
        .wx-search-btn:hover { color: var(--accent); }
        .wx-error {
            color: #f5a97f;
            font-size: 13px;
            text-align: center;
            margin-top: 10px;
            font-family: 'Space Mono', monospace;
        }

        /* --- Main card --- */
        .wx-main {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            backdrop-filter: blur(24px);
            padding: 40px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            animation: fadeUp 0.5s ease both;
        }
        .wx-main::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 60% at 10% 30%, rgba(94,140,220,0.07), transparent);
            pointer-events: none;
        }

        .wx-main-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }

        .wx-city-name {
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 600;
            letter-spacing: -2px;
            line-height: 1;
            color: var(--text-primary);
            margin: 0 0 6px;
        }
        .wx-city-desc {
            color: var(--text-muted);
            font-size: 15px;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .wx-temp-block {
            text-align: right;
            flex-shrink: 0;
        }
        .wx-temp-big {
            font-family: 'Space Mono', monospace;
            font-size: clamp(3.5rem, 9vw, 6.5rem);
            font-weight: 700;
            line-height: 1;
            color: var(--text-primary);
            letter-spacing: -3px;
        }
        .wx-temp-big sup {
            font-size: 0.35em;
            vertical-align: super;
            color: var(--text-muted);
            letter-spacing: 0;
        }
        .wx-icon {
            display: block;
            width: 80px;
            height: 80px;
            margin-left: auto;
            filter: drop-shadow(0 0 20px rgba(126,184,247,0.3));
        }

        /* Stats row */
        .wx-stats {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }
        .wx-stat {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .wx-stat-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .wx-stat-val {
            font-family: 'Space Mono', monospace;
            font-size: 15px;
            color: var(--text-primary);
            font-weight: 700;
        }
        .wx-stat svg { color: var(--accent); flex-shrink: 0; opacity: 0.7; }

        /* Detail link */
        .wx-detail-link {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }
        .wx-detail-link:hover {
            background: var(--glass-hover);
            color: var(--accent);
            border-color: rgba(126,184,247,0.3);
        }

        /* --- Hourly scroll --- */
        .wx-hourly-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }
        .wx-hourly-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: none;
        }
        .wx-hourly-scroll::-webkit-scrollbar { display: none; }
        .wx-hour-card {
            flex-shrink: 0;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 12px 14px;
            text-align: center;
            min-width: 72px;
            transition: border-color 0.2s, background 0.2s;
        }
        .wx-hour-card:hover {
            border-color: rgba(126,184,247,0.25);
            background: var(--glass-hover);
        }
        .wx-hour-time {
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .wx-hour-icon { width: 40px; height: 40px; margin: 0 auto; }
        .wx-hour-temp {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 4px;
        }

        /* --- Animations --- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Scrollbar global */
        * { box-sizing: border-box; }

        @media (max-width: 640px) {
            .wx-main { padding: 24px 18px; }
            .wx-main-top { gap: 1rem; }
        }
    </style>

    <div class="wx-wrap">
        <div style="max-width: 900px; margin: 0 auto;">

            {{-- SEARCH --}}
            <div class="wx-search-wrap">
                <form method="POST" action="{{ route('city.search') }}">
                    @csrf
                    <input
                        class="wx-search-input"
                        type="text"
                        name="city"
                        placeholder="Search city..."
                        autocomplete="off"
                    >
                    <button class="wx-search-btn" type="submit">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </button>
                </form>
                @if(session('error'))
                    <div class="wx-error">{{ session('error') }}</div>
                @endif
            </div>

            {{-- MAIN CITY --}}
            <div class="wx-main">
                <a class="wx-detail-link" href="{{ route('city.show', ['city' => $mainWeather['name']]) }}" title="Full details">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </a>

                <div class="wx-main-top">
                    <div>
                        <h1 class="wx-city-name">{{ $mainWeather['data']['name'] }}</h1>
                        <p class="wx-city-desc">{{ $mainWeather['data']['weather'][0]['description'] ?? '' }}</p>
                    </div>
                    <div class="wx-temp-block">
                        <img
                            class="wx-icon"
                            src="https://openweathermap.org/img/wn/{{ $mainWeather['data']['weather'][0]['icon'] ?? '01d' }}@2x.png"
                            alt=""
                        >
                        <div class="wx-temp-big">
                            {{ round($mainWeather['data']['main']['temp']) }}<sup>°C</sup>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="wx-stats">
                    <div class="wx-stat">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <div>
                            <div class="wx-stat-label">Feels like</div>
                            <div class="wx-stat-val">{{ round($mainWeather['data']['main']['feels_like']) }}°</div>
                        </div>
                    </div>
                    <div class="wx-stat">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 2v12M12 14s-5 2.5-5 6a5 5 0 0010 0c0-3.5-5-6-5-6z"/>
                        </svg>
                        <div>
                            <div class="wx-stat-label">Humidity</div>
                            <div class="wx-stat-val">{{ $mainWeather['data']['main']['humidity'] }}%</div>
                        </div>
                    </div>
                    <div class="wx-stat">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                        <div>
                            <div class="wx-stat-label">Wind</div>
                            <div class="wx-stat-val">{{ $mainWeather['data']['wind']['speed'] }} m/s</div>
                        </div>
                    </div>
                    <div class="wx-stat">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <div>
                            <div class="wx-stat-label">Pressure</div>
                            <div class="wx-stat-val">{{ $mainWeather['data']['main']['pressure'] }} hPa</div>
                        </div>
                    </div>
                </div>

                {{-- Hourly --}}
                @if(!empty($mainWeather['hourly']))
                    <div class="wx-hourly-title">Hourly forecast</div>
                    <div class="wx-hourly-scroll">
                        @foreach(array_slice($mainWeather['hourly'], 0, 10) as $hour)
                            <div class="wx-hour-card">
                                <div class="wx-hour-time">{{ \Carbon\Carbon::parse($hour['dt_txt'])->format('H:i') }}</div>
                                <img class="wx-hour-icon" src="https://openweathermap.org/img/wn/{{ $hour['weather'][0]['icon'] }}.png" alt="">
                                <div class="wx-hour-temp">{{ round($hour['main']['temp']) }}°</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 3. FAVORITES --}}
            @if(count($favorites) > 0)
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Favorites</h3>
                <div class="flex flex-wrap gap-6">
                    @foreach($favorites as $fav)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 relative group w-[240px] hover:shadow-xl transition-shadow">
                            {{-- Content --}}
                            <div class="text-center mb-4">
                                <img src="https://openweathermap.org/img/wn/{{ $fav['icon'] }}@2x.png" class="w-20 h-20 mx-auto mb-3">
                                <h4 class="font-bold text-xl dark:text-gray-100 mb-2">{{ $fav['name'] }}</h4>
                                <p class="text-gray-500 dark:text-gray-400 text-3xl font-bold">{{ $fav['temp'] }}°C</p>
                            </div>
                            {{-- Actions --}}
                            <div class="flex justify-center items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                {{-- 1. Set as Main --}}
                                <form action="{{ route('city.setMain') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="city" value="{{ $fav['name'] }}">
                                    <input type="hidden" name="source" value="favorite_swap">
                                    <button type="submit" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="Set as Main">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    </button>
                                </form>
                                {{-- 2. Details --}}
                                <a href="{{ route('city.show', ['city' => $fav['name']]) }}" class="p-2 bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                                {{-- 3. Remove --}}
                                <form action="{{ route('city.toggleFavorite') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="city" value="{{ $fav['name'] }}">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Remove">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
