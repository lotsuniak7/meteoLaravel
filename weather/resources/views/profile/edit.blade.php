<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- API TOKEN --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">API Token</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Generate a token to access the API from Postman or any external client.<br>
                        Creating a new token will invalidate the previous one.
                    </p>

                    @if(session('api_token'))
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-300 rounded-lg">
                            <p class="text-xs text-yellow-800 dark:text-yellow-200 font-bold mb-1">
                                ⚠ Copy this token now — it will not be shown again.
                            </p>
                            <code class="block text-sm break-all text-yellow-900 dark:text-yellow-100">
                                {{ session('api_token') }}
                            </code>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.token.generate') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-md text-sm font-medium hover:bg-gray-700 transition">
                            Generate new token
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
