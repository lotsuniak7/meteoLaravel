<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; color: #1a1a2e; background: #f5f7fa; margin: 0; padding: 0; }
        .wrap { max-width: 580px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #1e3a5f, #3b6ea5); padding: 32px; color: #fff; }
        .header h1 { margin: 0 0 4px; font-size: 22px; }
        .header p { margin: 0; opacity: 0.7; font-size: 14px; }
        .body { padding: 28px; }
        .city-block { margin-bottom: 28px; }
        .city-name { font-size: 17px; font-weight: 700; color: #1e3a5f; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #e8ecf0; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 8px 10px; background: #f0f4f8; color: #555; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 9px 10px; border-bottom: 1px solid #f0f0f0; color: #333; }
        tr:last-child td { border-bottom: none; }
        .footer { padding: 20px 28px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Daily Weather Forecast</h1>
        <p>{{ now()->format('l, d F Y') }} — CSV files are attached</p>
    </div>
    <div class="body">
        <p style="margin-top:0;color:#555;font-size:14px;">Hello {{ $recipient->name }},<br>Here is your daily forecast summary for your subscribed cities.</p>

        @foreach($forecasts as $cityName => $rows)
            <div class="city-block">
                <div class="city-name">📍 {{ $cityName }}</div>
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Condition</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{ $row['date'] }}</td>
                            <td>{{ $row['temp_min'] }}°C</td>
                            <td>{{ $row['temp_max'] }}°C</td>
                            <td>{{ ucfirst($row['description']) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    <div class="footer">Detailed CSV files are attached for each city.</div>
</div>
</body>
</html>
