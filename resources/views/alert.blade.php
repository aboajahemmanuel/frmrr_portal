@extends('layouts.external')

@section('content')
    <section class="main-container">
        <div class="np-container">
            <div class="np-content">
                <div class="np-flex">
                    <div class="n">
                        {{ $single_news->title }}
                    </div>
                    <div class="d">
                        @php
                            $postdate = date_format($single_news->created_at, 'F d,Y');

                        @endphp

                        <?php
                        
                        $timestamp = strtotime($postdate);
                        $newDateFormat = date('M. d, Y', $timestamp);
                        echo $newDateFormat;
                        
                        ?>
                    </div>
                </div>
                <!-- <img src="assets/art.png" alt="blog picture"> -->
                <div class="np-p">
                    {!! $single_news->news_content !!}
                </div>
            </div>
            <div class="other-news">
                <div class="otn">Top Five(5) Headlines</div>
                @foreach ($other_news as $other_news)
                    <a href="{{ route('alert', $other_news->id) }}">
                        <div class="news-card">
                            <div class="date"> @php
                                $postdate = date_format($other_news->created_at, 'F d,Y');

                            @endphp

                                <?php
                                
                                $timestamp = strtotime($postdate);
                                $newDateFormat = date('M. d, Y', $timestamp);
                                echo $newDateFormat;
                                
                                ?></div>
                            <div class="news-title">{{ $other_news->title }}</div>
                            <div class="news-desc">
                                {!! Illuminate\Support\Str::limit($other_news->news_content, 50) !!}
                            </div>
                        </div>
                    </a>
                @endforeach

                <a href="{{ url('newsalert') }}">
                    <div class="button-container" style="margin-left: 20px">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>See More</div>
                                <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Right Arrow" />
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>
    </div>
@endsection
</div>
</body>

</html>
