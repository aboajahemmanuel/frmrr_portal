  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')

          <div class="info">
              <div class="title">News </div>
              
      </div>
<style>
/* Premium Cache-immune Marquee News Card overrides */
.scrolling-news .news-card {
    background-color: #1d326d !important;
    border-top-left-radius: 16px !important;
    border-bottom-right-radius: 16px !important;
    border-bottom-left-radius: 16px !important;
    width: 190px !important; /* Expanded to prevent crowding */
    padding: 10px 16px !important; /* Perfect premium breathing room */
    color: #fff !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 2px !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
}

.scrolling-news .news-card .news-title {
    color: #fff !important;
    font-family: straightBold, sans-serif !important;
    font-size: 13.5px !important; /* Ultra-clean, premium header size */
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important; /* Guaranteed zero text spill */
    width: 100% !important;
    display: block !important;
}

.scrolling-news .news-card .date {
    color: rgba(227, 227, 238, 0.75) !important;
    font-family: customRegular, sans-serif !important;
    font-size: 11px !important;
    margin-bottom: 2px !important;
}
</style>
      <marquee class="marq" bgcolor="transparent" direction="left" loop="">
          <div class="scrolling-news">
              @foreach ($news_alert as $news)
              <a href="{{ route('alert', $news->id) }}">
                  <div class="news-card">
                      <div class="date"> @php
                          $postdate = date_format($news->created_at, 'F d,Y');

                          @endphp

                          <?php

                            $timestamp = strtotime($postdate);
                            $newDateFormat = date('M. j, Y', $timestamp);
                            echo $newDateFormat;

                            ?></div>
                      <div class="news-title">
                          {{ \Illuminate\Support\Str::limit($news->title, 50) }}
                      </div>
                      {{-- <div class="news-desc">
                              {{ Str::limit($news->news_content, 25) }}



                  </div> --}}
          </div>
          </a>
          @endforeach


          </div>
      </marquee>
  </section>