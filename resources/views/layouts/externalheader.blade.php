  <section class="top-half">
      <div class="w-1100">
          @include('layouts.appnavbar')

          <div class="info">
              <div class="title">The Financial Market Rules and Regulations Portal (FMRR)</div>
              <div class="desc">
                  FMRR serves as a comprehensive repository of financial market regulations, providing a strategic advantage in the dynamic financial markets landscape. This user-friendly platform facilitates the navigation of complex regulatory frameworks by offering real-time access to regulatory changes and updates. It streamlines the process of navigating rules and regulations, simplifies regulatory reporting and submissions, and acts as a centralised hub for all regulatory information and resources.
              </div>
              <?php
                $title = ''; 
                ?>
              <div style="display: flex; align-items:center; gap: 5px;">
                  <form method="GET" action="{{ route('search_result') }}" style="width: 100% !important;">
                      <div class="search" style="width: 100% !important;">
                          <div class="search-box">
                              <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                              <input required name="title" type="search" placeholder="What are you looking for?" />
                          </div>
                          <a href="#" style="height: 100%;">
                              <button style="height: 100%;" type="submit">
                                  <div class="search-full">Search</div>
                              </button>
                          </a>
                      </div>
                  </form> 
                  @if (Auth::check())
                  @if (($userSubscription ?? $isSubscribed) || Auth::user()->usertype == 'internal')
                  <a href="{{ url('search') }}">
                      <div>
                          <div class="gradient-buttons">
                              <div class="gradient-button-content-gold" style="text-align: center !important; padding: 3px !important;">
                                  <div>Advanced Search</div>
                              </div>
                          </div>
                      </div>
                  </a>
                  @endif
                  @endif



                  @if (Auth::check())
                  @if (!($userSubscription ?? $isSubscribed) && Auth::user()->usertype != 'internal')
                  <a href="{{ url('subscribe') }}">
                      <div>
                          <div class="gradient-buttons">
                              <div class="gradient-button-content-gold">
                                  <div>Advanced Search</div>
                              </div>
                          </div>
                      </div>
                  </a>
                  @endif
                  @else
                  <a href="{{ url('subscribe') }}">
                      <div>
                          <div class="gradient-buttons">
                              <div class="gradient-button-content-gold">
                                  <div>Advanced Search</div>
                              </div>
                          </div>
                      </div>
                  </a>
                  @endif
              </div>


              <div class="shortcuts">
                  <div>
                      <b>Market Product Tags</b>:
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
                          {{ substr($news->title, 0, 23) }}


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