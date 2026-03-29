<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Lucky Draw Settings') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="facebook_live_url" :value="__('Facebook Live URL')" />
                    <x-text-input
                        id="facebook_live_url"
                        class="block mt-1 w-full"
                        type="url"
                        name="facebook_live_url"
                        :value="old('facebook_live_url', $facebookLiveUrl)"
                        placeholder="https://www.facebook.com/yourpage/videos/123456789"
                    />
                    <x-input-error :messages="$errors->get('facebook_live_url')" class="mt-2" />
                    <p class="mt-2 text-sm text-gray-500">Paste the full Facebook Live video or page URL.</p>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <x-primary-button>
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
