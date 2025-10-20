@extends('layouts.external')

@section('content')
    <section class="main-container">
        <div class="cards-container">




            @foreach ($news_alert as $news)
                <div class="card js-card">
                    <div class="">
                        <a href="{{ route('alert', $news->id) }}">
                            <div class="blog-card">
                                <img src="{{ asset('public/users/assets/art.jpg') }}" alt="art">
                                <div class="blog-card-info">
                                    
                                    <div class="blog-card-title">{{ $news->title }}</div>
                                    <div class="blog-card-date"> {{ \Carbon\Carbon::parse($news->created_at)->format('M. j, Y') }}</div>
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
