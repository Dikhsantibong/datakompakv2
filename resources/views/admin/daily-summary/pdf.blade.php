<!DOCTYPE html>
<html>
<head>
    <title>Daily Summary Report - {{ $date }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f4f4f4; }
        h1 { text-align: center; color: #333; }
        .unit-name { margin-top: 20px; margin-bottom: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>Daily Summary Report - {{ $date }}</h1>

    @foreach($units as $unit)
        <h2 class="unit-name">{{ $unit->name }}</h2>
        <table>
            <!-- Add table headers and data similar to your blade view -->
            <!-- You might want to simplify the table for PDF format -->
        </table>
    @endforeach
</body>
</html> 