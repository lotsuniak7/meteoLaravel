<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 1. SEARCH FORM --}}
            <div class="mb-8">
                <form method="POST" action="{{ route('city.search') }}" class="relative max-w-lg mx-auto">
                    @csrf
                    <input type="text" name="city" placeholder="Search for a city..."
                           class="w-full pl-4 pr-12 py-3 rounded-full border-none shadow-lg focus:ring-2 focus:ring-indigo-500 text-gray-800">
                    <button type="submit" class="absolute right-2 top-1.5 bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
                @if(session('error'))
                    <div class="text-red-500 text-center mt-2">{{ session('error') }}</div>
                @endif
            </div>

            {{-- 2. MAIN CITY - BETTER LAYOUT --}}
            <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
                {{-- Кнопка "Плюс" для перехода к деталям --}}
                <a href="{{ route('city.show', ['city' => $mainWeather['name']]) }}"
                   class="absolute top-6 right-6 bg-white/20 hover:bg-white/30 p-3 rounded-full backdrop-blur-sm transition"
                   title="More Details">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>

                {{-- Horizontal LAYOUT --}}
                <div class="flex flex-row items-center justify-between gap-12">
                    {{-- Part left Name and desc --}}
                    <div class="text-left z-10 flex-shrink-0">
                        <h2 class="text-5xl font-bold mb-3">{{ $mainWeather['data']['name'] }}</h2>
                        <p class="text-2xl opacity-90 capitalize">{{ $mainWeather['data']['weather'][0]['description'] }}</p>
                    </div>

                    {{-- Centre big temperature --}}
                    <div class="z-10 flex-1 flex items-center justify-center">
                        <h1 class="text-9xl font-bold tracking-tight">{{ round($mainWeather['data']['main']['temp']) }}°</h1>
                    </div>

                    {{--part right: weather icon --}}
                    <div class="z-10 flex-shrink-0">
                        @if(isset($mainWeather['data']['weather'][0]['icon']))
                            <img src="https://openweathermap.org/img/wn/{{ $mainWeather['data']['weather'][0]['icon'] }}@4x.png"
                                 alt="weather icon" class="w-40 h-40 drop-shadow-2xl">
                        @endif
                    </div>
                </div>

                {{-- hourly forecast --}}
                @if(!empty($mainWeather['hourly']))
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <div class="flex justify-center gap-4 overflow-x-auto pb-2">
                            @foreach(array_slice($mainWeather['hourly'], 0, 8) as $hour)
                                <div class="flex flex-col items-center min-w-[70px] bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <span class="text-sm opacity-80 mb-1">{{ \Carbon\Carbon::parse($hour['dt_txt'])->format('H:i') }}</span>
                                    <img src="https://openweathermap.org/img/wn/{{ $hour['weather'][0]['icon'] }}.png" class="w-12 h-12 my-1">
                                    <span class="font-bold text-lg">{{ round($hour['main']['temp']) }}°</span>
                                </div>
                            @endforeach
                        </div>
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
