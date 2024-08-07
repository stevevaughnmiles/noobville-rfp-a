<?php
require '../vendor/autoload.php';

// Database connection
$dsn = 'mysql:host=database;dbname=mydatabase;charset=utf8mb4';
$user = 'lando';
$password = 'lando';

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Fetch events
    $eventsStmt = $pdo->query('SELECT * FROM events');
    $events = $eventsStmt->fetchAll();

    // Fetch deals
    $dealsStmt = $pdo->query('SELECT * FROM deals');
    $deals = $dealsStmt->fetchAll();

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    die();
}

// Prepare events for FullCalendar
$calendarEvents = [];
foreach ($events as $event) {
    $eventDates = json_decode($event['event_dates'], true);
    foreach ($eventDates as $date) {
        $calendarEvents[] = [
            'title' => $event['name'],
            'start' => $date['start_date'],
            'end' => $date['end_date'],
            'color' => 'blue'  // Color for events
        ];
    }
}

// Prepare deals for FullCalendar
foreach ($deals as $deal) {
    $calendarEvents[] = [
        'title' => $deal['title'],
        'start' => $deal['valid_start_date'],
        'end' => $deal['valid_end_date'],
        'color' => 'green'  // Color for deals
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar of Events & Deals</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?= json_encode($calendarEvents) ?>
            });
            calendar.render();
        });
    </script>
</body>
</html>
