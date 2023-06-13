<!DOCTYPE html>
<html>
<head>
    <title>Weather Forecast</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .input-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .input-container .form-control {
            width: 300px;
            margin-right: 10px;
        }

        .days-container {
            display: flex;
            flex-wrap: wrap;
        }

        .day-section {
            flex: 0 0 calc(20%); /* Adjust the width of each day */
            padding: 20px;
            margin-bottom: 30px;
            background-color: #fff;
        }

        .day-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .day-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .day-item {
            display: flex;
            align-items: flex-start; /* Adjust alignment of items within each day */
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .day-item img {
            width: 50px;
            margin-right: 20px;
        }

        .temperature {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .description {
            color: #888;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Weather Forecast</h1>

    <form action="{{ route('weather.update') }}" method="POST">
        @csrf
        <div class="input-container">
            <input type="text" id="ipAddress" name="ipAddress" value="{{ $ipAddress }}" class="form-control">
            <button type="submit" class="btn btn-primary">Get Weather</button>
        </div>
        @if(count($forecast) > 0)
            <div class="location text-center">
                Global Location: {{ $forecast[0]['location'] }}
            </div>
        @endif
    </form>

    <div class="days-container">
        @if ($forecast)
            @foreach ($forecast as $date => $dayForecast)
                <div class="day-section">
                    <div class="day-title">{{ date('F d', strtotime($dayForecast['date'])) }}</div>
                    <ul class="day-list">
                        <li class="day-item">
                            <img src="{{ $dayForecast['icon'] }}" alt="Weather Icon">
                            <div>
                                <div class="temperature">{{ $dayForecast['temperature'] }}°C</div>
                                <div class="description">{{ $dayForecast['description'] }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            @endforeach
        @else
            <p>No forecast available.</p>
        @endif
    </div>
</div>
</body>
</html>
