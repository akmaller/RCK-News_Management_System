@props(['cats' => collect()])

@php
    // palet default jika category->color kosong
    $palette = [
        '#EF4444', // red-500
        '#F59E0B', // amber-500
        '#10B981', // emerald-500
        '#3B82F6', // blue-500
        '#8B5CF6', // violet-500
        '#EC4899', // pink-500
        '#14B8A6', // teal-500
        '#F97316', // orange-500
    ];
@endphp

@if($cats->isNotEmpty())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            {{-- masker gradasi di sisi kiri/kanan biar manis --}}
            <div class="pointer-events-none absolute left-0 top-0 h-full w-8 bg-gradient-to-r from-white to-transparent"></div>
            <div class="pointer-events-none absolute right-0 top-0 h-full w-8 bg-gradient-to-l from-white to-transparent"></div>

            {{-- rail horizontal --}}
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent">
                <div class="flex items-center gap-3 px-4 py-3 min-w-full whitespace-nowrap">
                    @foreach($cats as $i => $cat)
                        @php
                            $hex = $cat->color ?? $palette[$i % count($palette)];
                        @endphp
                        <a href="{{ route('category.show', $cat->slug) }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-full text-white text-sm font-medium hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2"
                           style="background-color: chocolate;"
                           title="{{ $cat->name }}">
                           {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-ad-slot location="header" />
</div>
