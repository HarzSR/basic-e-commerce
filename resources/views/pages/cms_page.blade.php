@extends('layouts.frontLayout.front_design')

@section('content')

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="features_items">
                        <h2 class="title text-center">{{ $cmsPageDetails->title }}</h2>
                        <p>{{ $cmsPageDetails->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
