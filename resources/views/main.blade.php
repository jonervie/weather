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
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .content {
                border: 1px solid black;
                padding: 50px;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">

                <div class="title m-b-md">
                    5 day forecast for {{ $ipinfo->city }}, {{ $ipinfo->region }}
                </div>

                <div class="multiday">

                    @foreach ($weather_data[0]->days as $day)
                        <p>This is user {{ $day }}</p>
                    @endforeach

                </div>

            </div>
        </div>
    </body>
</html>
