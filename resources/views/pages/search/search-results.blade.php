@extends("layouts.app")

@section("breadcrumbs")
    {!! Breadcrumbs::render("search") !!}
@stop

@section("content")
    <div class="content-section__wrapper">
        <div class="content-section">

            <h1 class="page-title centered">Zoeken</h1>
        
            @include("partials.feedback")

            <form action="{{ route('search.post') }}" method="post">
                {{ csrf_field() }}
                
                Search bar here

            </form>

            Results here

        </div>
    </div>
@stop