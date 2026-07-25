@extends('layouts.site')

@section('title', 'Artikel')

@section('content')
    <x-site.hero
        eyebrow="Wawasan & Tips"
        title="Artikel Umroh & Haji"
        description="Tips persiapan, panduan ibadah, dan cerita seputar perjalanan Umroh & Haji."
        :crumbs="['Beranda' => url('/'), 'Artikel' => null]" />

    <section class="max-w-7xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">
        @if ($articles->isEmpty())
            <x-site.empty-state
                title="Belum ada artikel yang dipublikasikan"
                description="Nantikan artikel dan panduan terbaru seputar Umroh & Haji dari kami."
                :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-6 h-6\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25\' /></svg>'" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($articles as $i => $article)
                    <div style="transition-delay:{{ ($i % 3) * 80 }}ms">
                        <x-site.article-card :article="$article" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links('vendor.pagination.site') }}
            </div>
        @endif
    </section>
@endsection
