<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3 w-full">
            <x-filament-panels::avatar.user size="lg" />

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                    halo
                </p>
            </div>

            <a href="/">
                <x-filament::button color="gray" labeled-from="sm" tag="button" type="button">
                    Kembali ke beranda
                </x-filament::button>
            </a>
    </x-filament::section>
</x-filament-widgets::widget>
