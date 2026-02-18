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
            --surface: #0d0f14;
        }

        body, .min-h-screen, [class*="bg-gray"] {
            background-color: var(--surface) !important;
            color: var(--text-primary) !important;
            font-family: 'Syne', sans-serif !important;
        }

        .city-wrap {
            min-height: 100vh;
            padding: 2rem 1.5rem 5rem;
            background:
                radial-gradient(ellipse 90% 55% at 5% 0%, rgba(80,130,220,0.1) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 95% 90%, rgba(160,90,70,0.07) 0%, transparent 50%),
                var(--surface);
        }

        .city-inner { max-width: 860px; margin: 0 auto; }

        .city-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 0.5px;
            margin-bottom: 2.5rem;
            transition: color 0.2s;
        }
        .city-back:hover { color: var(--text-primary); }
        .city-back svg { transition: transform 0.2s; }
        .city-back:hover svg { transform: translateX(-3px); }

        .city-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
            animation: fadeUp 0.45s ease both;
        }
        .city-name {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 800;
            letter-spacing: -3px;
            line-height: 0.95;
            margin: 0 0 10px;
            color: var(--text-primary);
        }
        .city-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }
        .city-temp-side { text-align: right; }
        .city-hero-icon {
            width: 90px;
            height: 90px;
            display: block;
            margin-left: auto;
            filter: drop-shadow(0 0 28px rgba(126,184,247,0.25));
        }
        .city-temp-num {
            font-family: 'Space Mono', monospace;
            font-size: clamp(4rem, 10vw, 7rem);
            font-weight: 700;
            letter-spacing: -4px;
            line-height: 1;
            color: var(--text-primary);
        }
        .city-temp-num sup {
            font-size: 0.3em;
            vertical-align: super;
            color: var(--text-muted);
            letter-spacing: 0;
        }

        /* ── Action buttons ─────────────────────────────────────── */
        .city-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            animation: fadeUp 0.45s 0.07s ease both;
        }

        .ca-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            background: var(--glass);
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .ca-btn:hover {
            background: var(--glass-hover);
            color: var(--text-primary);
            border-color: rgba(255,255,255,0.15);
        }

        /* Saved = синяя рамка */
        .ca-btn.is-saved {
            border-color: rgba(126,184,247,0.4);
            color: var(--accent);
            background: rgba(126,184,247,0.07);
        }
        .ca-btn.is-saved:hover {
            background: rgba(126,184,247,0.12);
            color: #a8d4ff;
        }

        /* Favorite = золотая рамка */
        .ca-btn.is-fav {
            border-color: rgba(245,201,127,0.4);
            color: #f5c97f;
            background: rgba(245,201,127,0.07);
        }
        .ca-btn.is-fav:hover {
            background: rgba(245,201,127,0.12);
        }

        /* Report активен = зелёная */
        .ca-btn.is-report {
            border-color: rgba(100,200,130,0.35);
            color: #7ee8a2;
            background: rgba(100,200,130,0.06);
        }
        .ca-btn.is-report:hover {
            background: rgba(100,200,130,0.1);
        }

        /* Export кнопки */
        .ca-btn.export {
            border-color: rgba(255,255,255,0.06);
            font-size: 12px;
            padding: 8px 14px;
        }

        /* Подсказка под кнопками */
        .city-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 2rem;
            padding: 10px 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            animation: fadeUp 0.4s 0.1s ease both;
        }

        /* Stats */
        .city-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.45s 0.1s ease both;
        }
        .city-stat {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 18px;
            transition: border-color 0.2s;
        }
        .city-stat:hover { border-color: rgba(126,184,247,0.15); }
        .city-stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .city-stat-val {
            font-family: 'Space Mono', monospace;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .city-stat-sub {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .city-section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: var(--text-muted);
            margin-bottom: 14px;
            margin-top: 2.5rem;
        }

        /* Daily */
        .city-daily {
            display: flex;
            flex-direction: column;
            gap: 8px;
            animation: fadeUp 0.45s 0.15s ease both;
        }
        .city-day-row {
            display: flex;
            align-items: center;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            padding: 16px 22px;
            gap: 1rem;
            transition: border-color 0.2s, background 0.2s;
        }
        .city-day-row:hover {
            border-color: rgba(126,184,247,0.15);
            background: var(--glass-hover);
        }
        .city-day-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            flex: 1;
            min-width: 110px;
        }
        .city-day-desc {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: capitalize;
            flex: 1;
        }
        .city-day-icon { width: 40px; height: 40px; flex-shrink: 0; }
        .city-day-temps {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-shrink: 0;
        }
        .city-day-max {
            font-family: 'Space Mono', monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .city-day-min {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            color: var(--text-muted);
        }
        .city-day-bar-wrap {
            flex: 1;
            max-width: 120px;
            height: 4px;
            background: rgba(255,255,255,0.07);
            border-radius: 99px;
            overflow: hidden;
        }
        .city-day-bar {
            height: 100%;
            background: linear-gradient(90deg, #5e9ef0, #f5a97f);
            border-radius: 99px;
        }

        /* Flash messages */
        .flash-ok {
            background: rgba(100,200,130,0.08);
            border: 1px solid rgba(100,200,130,0.2);
            color: #7ee8a2;
            padding: 10px 16px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 1.5rem;
        }
        .flash-err {
            background: rgba(245,115,106,0.08);
            border: 1px solid rgba(245,115,106,0.2);
            color: #f5736a;
            padding: 10px 16px;
            border-radius: 9px;
            font-size: 13px;
            margin-bottom: 1.5rem;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        * { box-sizing: border-box; }

        @media (max-width: 600px) {
            .city-day-bar-wrap, .city-day-desc { display: none; }
            .city-day-row { padding: 14px; }
        }
    </style>

    <div class="city-wrap">
        <div class="city-inner">

            <a class="city-back" href="{{ route('dashboard') }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 12H5M5 12l7 7M5 12l7-7"/>
                </svg>
                Dashboard
            </a>

            @if(session('success'))
                <div class="flash-ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-err">{{ session('error') }}</div>
            @endif

            {{-- Hero --}}
            <div class="city-hero">
                <div>
                    <h1 class="city-name">{{ $city }}</h1>
                    <p class="city-subtitle">
                        {{ $current['weather'][0]['description'] ?? '' }}
                        &nbsp;·&nbsp;
                        {{ $current['sys']['country'] ?? '' }}
                    </p>
                </div>
                <div class="city-temp-side">
                    <img class="city-hero-icon"
                         src="https://openweathermap.org/img/wn/{{ $current['weather'][0]['icon'] ?? '01d' }}@2x.png"
                         alt="">
                    <div class="city-temp-num">
                        {{ round($current['main']['temp']) }}<sup>°C</sup>
                    </div>
                </div>
            </div>

            {{-- ── ACTIONS ── --}}
            <div class="city-actions">

                {{-- Save / Remove --}}
                @if(!$isSaved)
                    <form action="{{ route('city.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="city" value="{{ $city }}">
                        <button class="ca-btn" type="submit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                            Save to my cities
                        </button>
                    </form>
                @else
                    <form action="{{ route('city.remove', $city) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="ca-btn is-saved" type="submit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                            Saved — click to remove
                        </button>
                    </form>
                @endif

                {{-- Favorite — всегда видна, сохраняет автоматически --}}
                <form action="{{ route('city.favorite', $city) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="ca-btn {{ $isFavorite ? 'is-fav' : '' }}" type="submit">
                        {{ $isFavorite ? '★ Favorite city' : '☆ Set as favorite' }}
                    </button>
                </form>

                {{-- Daily report — только если город сохранён --}}
                @if($isSaved)
                    <form action="{{ route('city.daily-report', $city) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="ca-btn {{ $dailyReport ? 'is-report' : '' }}" type="submit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $dailyReport ? 'Daily report ON' : 'Daily report OFF' }}
                        </button>
                    </form>
                @endif

                {{-- Exports --}}
                <a class="ca-btn export" href="{{ route('city.export.xlsx', $city) }}">↓ XLSX</a>
                <a class="ca-btn export" href="{{ route('city.export.csv', $city) }}">↓ CSV</a>

            </div>

            {{-- Подсказка если город не сохранён --}}
            @if(!$isSaved)
                <div class="city-hint">
                    💡 Save this city to set it as favorite or subscribe to daily forecast emails.
                </div>
            @endif

            {{-- Stats --}}
            <div class="city-stats">
                <div class="city-stat">
                    <div class="city-stat-label">Feels like</div>
                    <div class="city-stat-val">{{ round($current['main']['feels_like']) }}°</div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Humidity</div>
                    <div class="city-stat-val">{{ $current['main']['humidity'] }}%</div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Wind</div>
                    <div class="city-stat-val">{{ $current['wind']['speed'] }}</div>
                    <div class="city-stat-sub">m/s</div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Pressure</div>
                    <div class="city-stat-val">{{ $current['main']['pressure'] }}</div>
                    <div class="city-stat-sub">hPa</div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Min / Max</div>
                    <div class="city-stat-val">
                        {{ round($current['main']['temp_min']) }}° / {{ round($current['main']['temp_max']) }}°
                    </div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Visibility</div>
                    <div class="city-stat-val">
                        @if(isset($current['visibility']))
                            {{ number_format($current['visibility'] / 1000, 1) }}
                            <span style="font-size:0.7em;color:var(--text-muted)">km</span>
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>

            {{-- 5-day forecast --}}
            @if(!empty($daily))
                <div class="city-section-label">5-day forecast</div>
                <div class="city-daily">
                    @php
                        $allMax   = array_column($daily, 'temp_max');
                        $allMin   = array_column($daily, 'temp_min');
                        $globalMax = max($allMax);
                        $globalMin = min($allMin);
                        $range    = max(1, $globalMax - $globalMin);
                    @endphp
                    @foreach($daily as $i => $day)
                        <div class="city-day-row" style="animation: fadeUp 0.4s {{ 0.15 + $i * 0.05 }}s ease both;">
                            <div class="city-day-name">{{ $day['date'] }}</div>
                            <div class="city-day-desc">{{ $day['description'] }}</div>
                            <img class="city-day-icon"
                                 src="https://openweathermap.org/img/wn/{{ $day['icon'] }}.png"
                                 alt="">
                            <div class="city-day-bar-wrap">
                                @php
                                    $barStart = (($day['temp_min'] - $globalMin) / $range) * 100;
                                    $barWidth = max(10, (($day['temp_max'] - $day['temp_min']) / $range) * 100);
                                @endphp
                                <div class="city-day-bar"
                                     style="margin-left:{{ $barStart }}%; width:{{ $barWidth }}%">
                                </div>
                            </div>
                            <div class="city-day-temps">
                                <span class="city-day-min">{{ round($day['temp_min']) }}°</span>
                                <span class="city-day-max">{{ round($day['temp_max']) }}°</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
