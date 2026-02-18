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
            margin-bottom: 3rem;
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
        .city-temp-side {
            text-align: right;
        }
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

        .city-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }
        .city-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            background: var(--glass);
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .city-action-btn:hover {
            background: var(--glass-hover);
            color: var(--text-primary);
        }
        .city-action-btn.active-main {
            border-color: rgba(126,184,247,0.35);
            color: var(--accent);
        }

        .city-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 3rem;
        }
        .city-stat {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 14px 18px;
            min-width: 140px;
            text-align: center;
        }
        .city-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .city-stat-val {
            font-family: 'Space Mono', monospace;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        @media (max-width: 640px) {
            .city-hero { gap: 1rem; }
            .city-name { font-size: clamp(2.8rem, 12vw, 4.5rem); }
            .city-temp-num { font-size: clamp(3.5rem, 18vw, 5.5rem); }
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

            <div class="city-hero">
                <div>
                    <h1 class="city-name">{{ $city }}</h1>
                    <p class="city-subtitle">{{ $current['weather'][0]['description'] ?? '' }} · {{ $current['sys']['country'] ?? '' }}</p>
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

            <div class="city-actions">
                @if($isMain)
                    <span class="city-action-btn active-main">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 10l7-7 7 7M12 3v18"/></svg>
                        Main city
                    </span>
                @else
                    <form action="{{ route('city.setMain') }}" method="POST">
                        @csrf
                        <input type="hidden" name="city" value="{{ $city }}">
                        <button type="submit" class="city-action-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 10l7-7 7 7M12 3v18"/></svg>
                            Set as main
                        </button>
                    </form>
                @endif

                @if($isFavorite)
                    <form action="{{ route('city.toggleFavorite') }}" method="POST">
                        @csrf
                        <input type="hidden" name="city" value="{{ $city }}">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="city-action-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Remove favorite
                        </button>
                    </form>
                @else
                    <form action="{{ route('city.toggleFavorite') }}" method="POST">
                        @csrf
                        <input type="hidden" name="city" value="{{ $city }}">
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="city-action-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                            Add to favorites
                        </button>
                    </form>
                @endif
            </div>

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
                    <div class="city-stat-val">{{ $current['wind']['speed'] }} m/s</div>
                </div>
                <div class="city-stat">
                    <div class="city-stat-label">Pressure</div>
                    <div class="city-stat-val">{{ $current['main']['pressure'] }} hPa</div>
                </div>
            </div>

            @if(!empty($hourly))
                <h3 class="text-2xl font-bold text-white mb-4">Today's Forecast</h3>
                <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                    @foreach(array_slice($hourly, 0, 12) as $hour)
                        <div class="flex flex-col items-center min-w-[80px] bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/20">
                            <span class="text-sm text-gray-300 mb-2">{{ \Carbon\Carbon::parse($hour['dt_txt'])->format('H:i') }}</span>
                            <img src="https://openweathermap.org/img/wn/{{ $hour['weather'][0]['icon'] }}.png" class="w-12 h-12 my-1">
                            <span class="font-bold text-xl text-white">{{ round($hour['main']['temp']) }}°</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(!empty($daily))
                <h3 class="text-2xl font-bold text-white mb-4 mt-10">5-Day Forecast</h3>
                <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                    @foreach($daily as $day)
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-lg min-w-[200px] hover:bg-white/15 transition">
                            <p class="font-bold text-lg text-white mb-2">{{ $day['date'] }}</p>
                            <div class="flex justify-center my-4">
                                <img src="https://openweathermap.org/img/wn/{{ $day['icon'] }}@2x.png" alt="icon" class="w-20 h-20">
                            </div>
                            <p class="text-sm text-gray-300 capitalize text-center mb-4">{{ $day['description'] }}</p>
                            <div class="text-center">
                                <div class="text-white text-xs mb-1">Max / Min</div>
                                <div>
                                    <span class="text-3xl font-bold text-white">{{ round($day['temp_max']) }}°</span>
                                    <span class="text-xl text-gray-400 ml-2">{{ round($day['temp_min']) }}°</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
