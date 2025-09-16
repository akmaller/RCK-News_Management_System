@php
    $heading = 'RCK News Management System';
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        icon="heroicon-o-information-circle"
        :heading="$heading"
    >
        <div class="text-sm text-neutral-400">
        </div>

        <div class="mt-4">
            <x-filament::button
                tag="a"
                href="https://www.rcknet.id/RCK_News_Management_System_Documentation.html#konfigurasi"
                target="_blank"
                icon="heroicon-o-book-open"
            >
                Dokumentasi
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
