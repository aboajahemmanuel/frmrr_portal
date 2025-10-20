  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')
          <div class="info">
              <div class="title">Search {{ $category->name }}</div>
              <div class="desc">
                  {{-- Browse through our frequently asked questions, tutorials, and
                  other self-help resources to find the answers you need. --}}
                  Explore our content with ease—start your search now.
              </div>
              <?php
                $title = '';
                ?>



              <form method="GET" action="{{ route('search_category') }}">
                  <div class="search">
                      <div class="search-box">
                          <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                          <input hidden name="category_slug" value="{{ $category->slug }}">
                          <input required name="title" type="search" placeholder="What are you looking for?" />
                      </div>
                      <a href="#" style="height: 100%;">
                          <button style="height: 100%;" type="submit">
                              <div class="search-full">Search</div>
                          </button>
                      </a>
                  </div>
              </form>
              <div class="shortcuts">
                  <div>
                      <b>Browse Shortcuts</b>:
                      <span>
                          @foreach ($data as $category)
                          <a href="{{ route('categorypages', $category->slug) }}"
                              style="color:#C79D51 !important">{{ trim($category->name) }}</a>@if (!$loop->last),
                          @endif
                          @endforeach
                      </span>
                  </div>
              </div>
          </div>
      </div>
      {{-- <marquee class="marq" bgcolor="transparent" direction="left" loop="">
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
              {{ Illuminate\Support\Str::limit($news->title, 25) }}

          </div>
          <div class="news-desc">
              {{ Illuminate\Support\Str::limit($news->news_content, 50) }}
          </div>
      </div>
      </a>
      @endforeach


      </div>
      </marquee> --}}
  </section>