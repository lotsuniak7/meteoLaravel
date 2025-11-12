<x-app-layout>
    <x-slot name="header">
        <h2>Weather</h2>
    </x-slot>

    <div class="p-6">
        <form method="GET" action="{{ route('weather.index') }}">
            <label for="place">City</label>
            <input id="place" name="place" value="{{ request('place') }}">
            <button type="submit">Show</button>
        </form>

        @if($city)
            <p class="mt-4">Requested city: <strong>{{ $city }}</strong></p>
            <p>TODO: show current weather here…</p>
        @endif
    </div>
</x-app-layout>
