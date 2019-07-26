<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>5 day forecast</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Arial', sans-serif;
                height: 100vh;
                margin: 0;
            }

            .title {
                font-size: 26px;
            }

            .content {
                border: 1px solid black;
                padding: 50px;
            }

            .daybox {
                display: inline-block;
                border: 2px solid #ccc;
                padding: 20px;
                margin-top: 10px;
            }

            .daybox span {
                color: black;
                font-weight: bolder !important;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content container-fluid">

                <div class="title">
                    5 day forecast for {{ $ipinfo->city }}, {{ $ipinfo->region }}
                </div>

                <div class="multiday">
                    <div>
                        @foreach ($weather_data->days as $day)
                            <div class="daybox">
                                <span>{{ $day[0]->main }}</span><br/>
                                <img src="http://openweathermap.org/img/w/{{ $day[0]->icon }}.png"><br/>
                                Min temp: {{ $day[0]->min_temp }} <br/>
                                Max temp: {{ $day[0]->max_temp }} <br/>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
