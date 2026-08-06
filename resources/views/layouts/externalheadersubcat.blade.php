  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')
          <div class="info">
              <div class="title">{{ $subcategory->name ?? ($category->name ?? 'Subcategory') }}</div>
              <div class="desc">
                   {{ $subcategory->summary  }}
              </div>
               
              <?php
                $title = '';
                ?>
              <form method="GET" action="{{ route('search_subcategory') }}">
                  <div class="search">
                      <div class="search-box">
                          <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                          <input hidden name="subcategory_slug" value="{{ $subcategory->slug }}">
                          <input required name="title" type="search" placeholder="Search within this subcategory..." />
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
                      <b>Markets and Productss</b>:
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
