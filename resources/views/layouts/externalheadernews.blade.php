  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')

          <div class="info">
              <div class="title">News </div>
              
      </div>
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
                          {{ substr($news->title, 0, 20) }}


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