@extends('layouts.externalnews')

@section('content')
<style>
/* Premium Compact News Redesign Overrides */
.cards-container {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)) !important;
    gap: 24px !important;
    width: 100% !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 20px 0 !important;
    box-sizing: border-box !important;
    align-items: start !important; /* Force all cards to align perfectly to the top of the row */
}

.card.js-card {
    display: block !important; /* Overrides Bootstrap/Dashlite's centering flexbox */
    width: 100% !important;
    height: auto !important;
    perspective: none !important;
    position: static !important;
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
    vertical-align: top !important; /* Secondary top alignment fallback */
}

.blog-card {
    background: #ffffff !important;
    border-top-left-radius: 20px !important;
    border-bottom-left-radius: 20px !important;
    border-bottom-right-radius: 20px !important;
    border-top-right-radius: 0px !important;
    overflow: hidden !important;
    box-shadow: 0 4px 15px rgba(29, 50, 109, 0.03) !important;
    transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    position: relative !important;
    border: 1px solid rgba(29, 50, 109, 0.05) !important;
    display: flex !important;
    flex-direction: column !important;
    min-height: 220px !important;
    height: auto !important;
    top: auto !important;
    left: auto !important;
    vertical-align: top !important; /* Secondary top alignment fallback */
}

.blog-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 10px 24px rgba(29, 50, 109, 0.08) !important;
    border-color: rgba(199, 157, 81, 0.3) !important;
}

.blog-card img {
    width: 100% !important;
    height: 130px !important;
    object-fit: cover !important;
    border-top-left-radius: 19px !important;
    border-top-right-radius: 0px !important;
    transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
}

.blog-card:hover img {
    transform: scale(1.06) !important;
}

.blog-card-info {
    padding: 14px !important;
    display: flex !important;
    flex-direction: column !important;
    flex-grow: 1 !important;
    justify-content: space-between !important;
}

.blog-card-title {
    color: #1d326d !important;
    font-family: straightBold, "Roboto", sans-serif !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    line-height: 1.35 !important;
    margin: 0 0 10px 0 !important;
    transition: color 0.3s ease !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
}

.blog-card:hover .blog-card-title {
    color: #C79D51 !important;
}

.blog-card-date {
    font-family: customRegular, sans-serif !important;
    color: #969698 !important;
    font-size: 11px !important;
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
    margin-top: auto !important;
}
</style>

    <section class="main-container">
        <div class="cards-container">
            @foreach ($news_alert as $news)
                <div class="card js-card">
                    <div class="">
                        <a href="{{ route('alert', $news->id) }}">
                            <div class="blog-card">
                                <img src="{{ asset('public/users/assets/art.jpg') }}" alt="{{ $news->title }}">
                                <div class="blog-card-info">
                                    <div class="blog-card-title">{{ $news->title }}</div>
                                    <div class="blog-card-date">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        {{ \Carbon\Carbon::parse($news->created_at)->format('M. j, Y') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    </div>
@endsection
</body>

</html>
