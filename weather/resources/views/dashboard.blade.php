<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-wrap items-end">

                        <form method="POST" action="{{ route('dashboard') }}" class="mr-4">
                            @csrf
                            <label for="city" class="block text-sm font-medium">Enter a city:</label>
                            <input type="text" id="city" name="city" placeholder="e.g. Paris"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit"
                                    class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Check Weather
                            </button>
                        </form>

                        <form method="POST" action="{{ route('dashboard') }}">
                            @csrf
                            <input type="hidden" name="action" value="clear">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                Clear List
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(isset($formError) && $formError)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm sm:rounded-lg" role="alert">
                    <p><strong>Error:</strong> {{ $formError }}</p>
                </div>
            @endif

            @foreach($weatherDataList as $weatherData)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        @if($weatherData['error'])
                            <h2 class="text-2xl font-semibold">{{ $weatherData['city'] }}</h2>
                            <p class="text-red-500">Could not load data: {{ $weatherData['error'] }}</p>

                        @else
                            <h2 class="text-2xl font-semibold">{{ $weatherData['current']['name'] }}</h2>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">

                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-center">
                                    <p class="text-sm">Temperature</p>
                                    <p class="text-3xl font-bold">{{ round($weatherData['current']['main']['temp']) }}°C</p>
                                </div>

                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-center">
                                    <p class="text-sm">Weather</p>
                                    <p class="text-xl">{{ $weatherData['current']['weather'][0]['description'] }}</p>
                                </div>

                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-center">
                                    <p class="text-sm">Humidity</p>
                                    <p class="text-xl">{{ $weatherData['current']['main']['humidity'] }}%</p>
                                </div>

                                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg text-center">
                                    <p class="text-sm">Wind</p>
                                    <p class="text-xl">{{ number_format($weatherData['current']['wind']['speed'], 1) }}</p>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
