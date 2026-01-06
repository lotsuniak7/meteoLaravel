<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER: Back + Actions --}}
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('dashboard') }}" class="flex items-center text-gray-300 hover:text-white transition text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>

                <div class="flex gap-3">
                    @if(!$isMain)
                        <form action="{{ route('city.setMain') }}" method="POST">
                            @csrf
                            <input type="hidden" name="city" value="{{ $city }}">
                            <input type="hidden" name="source" value="detail">
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-lg backdrop-blur-sm transition border border-white/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Set as Main
                            </button>
                        </form>
                    @else
                        <span class="px-5 py-2.5 bg-green-500/20 text-green-300 rounded-lg border border-green-500/30 flex items-center gap-2 cursor-default font-medium backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Current Main
                        </span>
                    @endif

                    <form action="{{ route('city.toggleFavorite') }}" method="POST">
                        @csrf
                        <input type="hidden" name="city" value="{{ $city }}">
                        @if($isFavorite)
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-300 border border-red-500/30 rounded-lg transition font-medium backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                Remove Favorite
                            </button>
                        @else
                            <input type="hidden" name="action" value="add">
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-lg transition font-medium backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Add to Favorites
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            {{-- MAIN INFO --}}
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden shadow-2xl rounded-3xl mb-8 text-white">
                <div class="p-10 flex flex-row items-center justify-between">
                    <div class="flex-shrink-0">
                        <h1 class="text-6xl font-extrabold mb-3">{{ $city }}</h1>
                        <p class="text-2xl opacity-90 capitalize">{{ $current['weather'][0]['description'] }}</p>
                    </div>

                    <div class="flex items-center gap-12">
                        <img src="https://openweathermap.org/img/wn/{{ $current['weather'][0]['icon'] }}@4x.png" alt="icon" class="w-48 h-48 drop-shadow-2xl">
                        <p class="text-9xl font-bold">{{ round($current['main']['temp']) }}°</p>
                    </div>
                </div>
            </div>

            {{-- COMPACT ADDITIONAL INFORMATION - IN ONE LINE --}}
            <div class="flex gap-8 justify-center mb-8">
                {{-- Humidity --}}
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-500/30 rounded-xl backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide">Humidity</p>
                        <p class="text-white text-2xl font-bold">{{ $current['main']['humidity'] }}%</p>
                    </div>
                </div>

                {{-- Wind Speed --}}
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-cyan-500/30 rounded-xl backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide">Wind</p>
                        <p class="text-white text-2xl font-bold">{{ $current['wind']['speed'] }} m/s</p>
                    </div>
                </div>

                {{-- Pressure --}}
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-purple-500/30 rounded-xl backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wide">Pressure</p>
                        <p class="text-white text-2xl font-bold">{{ $current['main']['pressure'] }} hPa</p>
                    </div>
                </div>
            </div>

            {{-- HOURLY FORECAST (if exist) --}}
            @if(!empty($hourly))
                <div class="mb-8">
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
                </div>
            @endif

            {{-- 5-DAY FORECAST --}}
            @if(!empty($daily))
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-white mb-4">5-Day Forecast</h3>
                    <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                        @foreach($daily as $day)
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-lg min-w-[200px] hover:bg-white/15 transition">
                                {{-- day of week --}}
                                <p class="font-bold text-lg text-white mb-2">{{ $day['date'] }}</p>

                                {{-- Icon --}}
                                <div class="flex justify-center my-4">
                                    <img src="https://openweathermap.org/img/wn/{{ $day['icon'] }}@2x.png" alt="icon" class="w-20 h-20">
                                </div>

                                {{-- Desc --}}
                                <p class="text-sm text-gray-300 capitalize text-center mb-4">{{ $day['description'] }}</p>

                                {{-- Temp MAX° / min° --}}
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
                </div>
            @endif

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>
