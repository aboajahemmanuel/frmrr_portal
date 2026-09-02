@extends('layouts.external')

@section('content')
<style>
/* Alert Page Layout & Blue Box Alignment Styles */
.np-container {
    display: flex;
    gap: 40px;
    align-items: flex-start;
    max-width: 1200px;
    margin: 30px auto 60px auto;
    padding: 0 20px;
    box-sizing: border-box;
}

.np-content {
    flex: 1;
    min-width: 0;
}

.np-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 24px;
    margin-bottom: 24px;
}

.n {
    font-family: straightBold, sans-serif;
    font-size: 24px;
    line-height: 1.35;
    color: #1d326d;
    flex: 1;
    margin: 0;
}

.d {
    color: #969698;
    font-family: customRegular, sans-serif;
    font-size: 13px;
    white-space: nowrap;
    flex-shrink: 0;
    margin-top: 6px;
}

.np-p {
    font-family: customRegular, sans-serif;
    font-size: 15.5px;
    line-height: 1.75;
    color: #333333;
    text-align: justify;
}

/* Sidebar: Other News */
.other-news {
    width: 290px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
}

.otn {
    font-family: straightBold, sans-serif;
    font-size: 20px;
    color: #1d326d;
    margin-bottom: 14px;
    line-height: 1.2;
}

/* The Blue Box */
.other-news-box {
    background-color: #1d326d !important;
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    border-top-right-radius: 0px;
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 0 4px 16px rgba(29, 50, 109, 0.12);
}

.other-news-item {
    text-decoration: none !important;
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    transition: transform 0.2s ease;
}

.other-news-item:last-of-type {
    border-bottom: none;
    padding-bottom: 2px;
}

.other-news-item:hover {
    transform: translateX(3px);
}

.other-news-item .other-news-date {
    color: #C79D51 !important;
    font-family: customRegular, sans-serif;
    font-size: 12px;
    letter-spacing: 0.2px;
}

.other-news-item .other-news-title {
    color: #ffffff !important;
    font-family: straightBold, sans-serif;
    font-size: 13.5px;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: color 0.2s ease;
}

.other-news-item:hover .other-news-title {
    color: #ebc277 !important;
}

/* Button inside the Blue Box */
.other-news-more-btn {
    text-decoration: none !important;
    display: block;
    width: 100%;
    margin-top: 4px;
}

.other-news-more-btn .gradient-buttons {
    width: 100% !important;
    margin: 0 !important;
    padding: 1px !important;
    box-sizing: border-box !important;
    border-top-left-radius: 12px !important;
    border-bottom-left-radius: 12px !important;
    border-bottom-right-radius: 12px !important;
}

.other-news-more-btn .gradient-button-content {
    width: 100% !important;
    padding: 10px 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    box-sizing: border-box !important;
    border-top-left-radius: 12px !important;
    border-bottom-left-radius: 12px !important;
    border-bottom-right-radius: 12px !important;
}

.other-news-more-btn .gradient-button-content div {
    white-space: nowrap !important;
    font-size: 13px !important;
    font-family: customBold, sans-serif !important;
}

.other-news-more-btn .gradient-button-content img {
    width: 14px !important;
    height: 14px !important;
}

@media (max-width: 900px) {
    .np-container {
        flex-direction: column;
    }
    .other-news {
        width: 100%;
    }
}
</style>

    <section class="main-container">
        <div class="np-container">
            <div class="np-content">
                <div class="np-flex">
                    <div class="n">
                        {{ $single_news->title }}
                    </div>
                    <div class="d">
                        {{ str_replace('May.', 'May', \Carbon\Carbon::parse($single_news->created_at)->format('M. j, Y')) }}
                    </div>
                </div>
                <!-- <img src="assets/art.png" alt="blog picture"> -->
                <div class="np-p">
                    {!! $single_news->news_content !!}
                </div>
            </div>
            <div class="other-news">
                <div class="otn">Top Five (5) Headlines</div>
                <div class="other-news-box">
                    @foreach ($other_news as $item)
                        <a href="{{ route('alert', $item->id) }}" class="other-news-item">
                            <div class="other-news-date">
                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($item->created_at)->format('M. j, Y')) }}
                            </div>
                            <div class="other-news-title">{{ $item->title }}</div>
                        </a>
                    @endforeach

                    <a href="{{ url('newsalert') }}" class="other-news-more-btn">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>See More</div>
                                <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Right Arrow" />
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
