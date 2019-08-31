@extends('_template')

@section('content')
    {{--<div class='row'>--}}
        {{--<div class='col-md-12'>--}}
            {{--<h1>Sensors</h1>--}}
        {{--</div>--}}
    {{--</div>--}}
<style>
    a.small-box-footer.off.relay-control {
        background-color: red;
    }
    a.small-box-footer.relay-control.on
    {
        background-color: green;
    }
</style>
<div class="row">
    @foreach ($ralays as $key=>$value)
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><i class="fa fa-sliders"></i> {{ $value['name'] }}</h3>
                <p class="relay-status" data-id="{{ $value['topic'] }}">{{ $value['state'] }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-server"></i>
            </div>
            <a class="small-box-footer {{ $value['state']  }} relay-control" data-id="{{ $value['id'] }}">Переключить <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    @endforeach
</div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow stateEmergencyStop">
                <div class="inner">
                    <h3><i class="fa fa-sliders"></i> Откл. все</h3>
                    <p>Аварийный останов</p>
                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a class="small-box-footer off emergencyStop" data-id="water/alarm">Аварийно остановить <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow leakage-control" data-id="water/leakage">
                <div class="inner">
                    <h3><i class="fa fa-sliders"></i> Датчик</h3>
                    <p class="leakage-status" data-id="water/leakage">Норма</p>
                    <audio id="carteSoudCtrl">
                        <source id="emergency" src="https://uberserver.ru/sounds/emergency.mp3" type="audio/mpeg">
                    </audio>
                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a class="small-box-footer">Датчик протечки воды </a>
            </div>
        </div><!-- ./col -->
    </div>
</div><!-- /.row -->

@endsection