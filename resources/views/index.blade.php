@extends('_template')

@section('content')

<div class='row'>
    <div class="section bg1 clearfix">
        <div class="content">
            <div class="overlay">
                <div class="overlaycontent">
                    <h1><a title="" href="/"><strong>UBERSERVER</strong> - умный дом</a></h1>
                </div>
            </div>
            <div class="fullwidth col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <img class="img-thumbnail" src="{{ asset("img/escape.jpg") }}" alt="face on Bot">
                </div>
                <div class="col-lg-8 col-md-6 col-xs-12">
                    <div class="form-group">
                        <label>Диалог:</label>
                        @foreach ($speech as $label)
                        <p><label>{{ $label }}</label></p>
                        @endforeach
                        <hr>
                        @foreach ($actions as $link => $action)
                        <a class="btn btn-primary" href="?act={{ $link }}">{{ $action }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
@endsection