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
    .text-dark{
        color:#212529;
    }

    .small-box>.small-box-footer {
        cursor: pointer;
    }
</style>
<div class="row">
    @foreach ($ralays as $key=>$value)
    <div class="col-lg-3 col-sm-6 col-md-3 col-xs-12">
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
        <div class="col-lg-3 col-sm-6 col-md-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow leakage-control" data-id="water/leakage">
                <div class="inner">
                    <h3><i class="fa fa-sliders"></i> Датчик</h3>
                    <p class="leakage-status" data-id="water/leakage">Норма</p>

                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a class="small-box-footer">Датчик протечки воды </a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-sm-6 col-md-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow timer-control" data-id="1">
                <div class="inner">
                    <h3><i class="fa fa-sliders"></i> Таймер</h3>
                    <p class="boiler-timer">
                        <select name="timer01" data-time="timer01" class="text-dark">
                            <option value="40">40 мин.</option>
                            <option value="30" selected>30 мин.</option>
                            <option value="20">20 мин.</option>
                            <option value="10">10 мин.</option>
                        </select>
                        <span class="timer-information"></span>
                    </p>
                </div>
                <div class="icon">
                    <i class="fa fa-server"></i>
                </div>
                <a class="small-box-footer timer-click">Запустить таймер</a>
            </div>
        </div><!-- ./col -->
    </div>
</div><!-- /.row -->

@endsection
