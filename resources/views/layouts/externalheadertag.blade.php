  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')
          <div class="info">
              <div class="title">{{ $marketTag->name }} Documents</div>
              <div class="desc">
                  <!-- Browse documents tagged with {{ $marketTag->name }}. Use the search below to find specific documents. -->
                   Explore our content with ease—start your search
              </div>
              <?php
                $title = '';
                ?>


              <form method="GET" action="{{ route('search_market_tag') }}">
                  <div class="search">
                      <div class="search-box">
                          <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                          <input hidden name="market_tag_slug" value="{{ $marketTag->slug }}">
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
                      <b>Markets and Products</b>:
                      <span>
                          @foreach ($marketProductTags as $tag)
                          <a href="{{ route('marketProductTag', $tag->slug) }}"
                              style="color:#C79D51 !important">{{ trim($tag->name) }}</a>@if (!$loop->last),
                          @endif
                          @endforeach
                      </span>
                  </div>
              </div>
          </div>
      </div>
  </section>
