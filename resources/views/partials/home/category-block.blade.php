@props([
    'title' => 'Kategori',
    'posts' => collect(),
    'moreUrl' => '#',
])

<section class="bg-white rounded-2xl shadow ring-1 ring-neutral-200 overflow-hidden">
    {{-- Header gradien --}}
    <div class="bg-gradient-to-r from-orange-700 to-orange-500 px-4 sm:px-5 py-3">
        <div class="text-white italic tracking-wider font-extrabold text-lg">
            {{ strtoupper($title) }}
        </div>
    </div>

    <div class="divide-y divide-neutral-200">
        @forelse ($posts as $post)
            <a href="{{ $post->permalink }}" class="flex gap-4 p-4 hover:bg-neutral-50">
                <div class="shrink-0 w-28 h-20 sm:w-32 sm:h-24 overflow-hidden rounded-xl ring-1 ring-neutral-200">
                    <img
                        src="{{ $post->thumbnail ? asset('storage/'.$post->thumbnail) : 'https://picsum.photos/220/160?random='.$post->id }}"
                        alt="{{ $post->title }}"
                        class="w-full aspect-[16/9] object-cover" decoding="async" loading="lazy">
                </div>
                <div class="flex-1">
                    <h3 class="text-base sm:text-lg font-semibold leading-snug">
                        {{ $post->title }}
                    </h3>
                    <div class="mt-1 text-xs text-neutral-500">
                        {{ $post->published_at?->translatedFormat('d M Y') }}
                    </div>
                </div>
            </a>
        @empty
            <div class="p-5 text-sm text-neutral-500">Belum ada artikel.</div>
        @endforelse
    </div>

    {{-- Footer link --}}
    <div class="px-4 sm:px-5 py-3">
        <a href="{{ $moreUrl }}" class="text-sm font-medium text-orange-500 hover:text-orange-700">
            Lihat lebih banyak →
        </a>
    </div>
</section>
