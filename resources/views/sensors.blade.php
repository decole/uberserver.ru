@extends('_template')

@section('content')
    {{--<div class='row'>--}}
        {{--<div class='col-md-12'>--}}
            {{--<h1>Sensors</h1>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Пристройка</span>
                    <span class="info-box-number">t.
                        <span class="sensor-state" data-topic="margulis/temperature">
                            {{ $sensors['margulis_temperature'] }}
                        </span> &#8451;
                    </span>
                    <span class="info-box-number"><i class="fa fa-fire"></i>
                        <span class="sensor-state" data-topic="margulis/humidity">
                            {{ $sensors['margulis_humidity'] }}
                        </span> %
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Холодная прихожка</span>
                    <span class="info-box-number">t.
                        <span class="sensor-state" data-topic="holl/temperature">
                            {{ $sensors['holl_temperature'] }}
                        </span> &#8451;
                    </span>
                    <span class="info-box-number"><i class="fa fa-fire"></i>
                        <span class="sensor-state" data-topic="holl/humidity">
                            {{ $sensors['holl_humidity'] }}
                        </span> %
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Низа</span>
                    <span class="info-box-number">t.
                        <span class="sensor-state"  data-topic="underflor/temperature">
                            {{ $sensors['underflor_temperature'] }}
                        </span> &#8451;
                    </span>
                    <span class="info-box-number"><i class="fa fa-fire"></i>
                        <span class="sensor-state" data-topic="underflor/humidity">
                            {{ $sensors['underflor_humidity'] }}
                        </span> %
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Коридор в низа</span>
                    <span class="info-box-number">t.
                        <span class="sensor-state" data-topic="underground/temperature">
                            {{ $sensors['underground_temperature'] }}
                        </span> &#8451;
                    </span>
                    <span class="info-box-number"><i class="fa fa-fire"></i>
                        <span class="sensor-state" data-topic="underground/humidity">
                            {{ $sensors['underground_humidity'] }}
                        </span> %
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
    </div>
    <div class='row'>
    <div class='col-md-6'>
        <!-- Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">GisMeteo погода</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <!-- Gismeteo informer START -->
                <link rel="stylesheet" type="text/css" href="https://nst1.gismeteo.ru/assets/flat-ui/legacy/css/informer.min.css">
                <div id="gsInformerID-ThDE2N1BwRI0IR" class="gsInformer" style="margin: auto;">
                    <div class="gsIContent">
                        <div id="cityLink">
                            <a href="https://www.gismeteo.ru/weather-kamyshin-5064/" target="_blank">Погода в Камышине</a>
                        </div>
                        <div class="gsLinks">
                            <table>
                                <tr>
                                    <td>
                                        <div class="leftCol">
                                            <a href="https://www.gismeteo.ru/" target="_blank">
                                                <img alt="Gismeteo" title="Gismeteo" src="https://nst1.gismeteo.ru/assets/flat-ui/img/logo-mini2.png" align="middle" border="0" />
                                                <span>Gismeteo</span>
                                            </a>
                                        </div>
                                        <div class="rightCol">
                                            <a href="https://www.gismeteo.ru/weather-kamyshin-5064/2-weeks/" target="_blank">Прогноз на 2 недели</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <script async src="https://www.gismeteo.ru/api/informer/getinformer/?hash=ThDE2N1BwRI0IR" type="text/javascript"></script>
                <!-- Gismeteo informer END -->
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class='col-md-6'>
        <!-- Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">AccuWeather погода</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <ul>
                    <li class="accu">Температура: {{ $acuweather->temperature }}</li>
                    <li class="accu">Состояние: {{ $acuweather->spec }}</li>
                    <li class="accu">Дата: {{ $max }}</li>
                </ul>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->

    </div><!-- /.row -->

@endsection