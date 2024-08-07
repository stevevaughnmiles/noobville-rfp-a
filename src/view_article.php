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

    if (!isset($_GET['id'])) {
        echo "No article ID provided.";
        exit;
    }

    $articleId = $_GET['id'];

    // Fetch article
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$articleId]);
    $article = $stmt->fetch();

    if (!$article) {
        echo "Article not found.";
        exit;
    }

    $listingIds = json_decode($article['listing_ids'], true);
    $eventIds = json_decode($article['event_ids'], true);

    // Fetch related listings
    if (!empty($listingIds)) {
        $inQuery = implode(',', array_fill(0, count($listingIds), '?'));
        $stmt = $pdo->prepare("SELECT * FROM listings WHERE id IN ($inQuery)");
        $stmt->execute($listingIds);
        $listings = $stmt->fetchAll();
    } else {
        $listings = [];
    }

    // Fetch related events
    if (!empty($eventIds)) {
        $inQuery = implode(',', array_fill(0, count($eventIds), '?'));
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id IN ($inQuery)");
        $stmt->execute($eventIds);
        $events = $stmt->fetchAll();
    } else {
        $events = [];
    }

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="article-container">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <img src="<?= htmlspecialchars(json_decode($article['image'], true)['url']) ?>" alt="<?= htmlspecialchars(json_decode($article['image'], true)['caption']) ?>" style="max-width: 400px;">
        <div class="article-content">
            <?= $article['content'] ?>
        </div>
        
        <?php if (!empty($listings)): ?>
            <h2>Related Listings</h2>
            <div class="related-listings">
                <?php foreach ($listings as $listing): ?>
                    <div class="listing">
                        <h3><?= htmlspecialchars($listing['name']) ?></h3>
                        <p><strong>Address:</strong> <?= htmlspecialchars($listing['address_line_1']) ?>, <?= htmlspecialchars($listing['city']) ?>, <?= htmlspecialchars($listing['state']) ?> <?= htmlspecialchars($listing['postcode']) ?></p>
                        <p><strong>Latitude:</strong> <?= htmlspecialchars($listing['latitude']) ?> | <strong>Longitude:</strong> <?= htmlspecialchars($listing['longitude']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($listing['phone_local']) ?></p>
                        <p><strong>Hours:</strong> 
                            <?php 
                            $hours = json_decode($listing['hours'], true);
                            foreach ($hours as $hour) {
                                echo htmlspecialchars("{$hour['dayOfWeek']}: {$hour['openAt']} - {$hour['closeAt']}<br>");
                            }
                            ?>
                        </p>
                        <p><strong>Amenities:</strong> <?= htmlspecialchars(implode(', ', array_column(json_decode($listing['amenities'], true), 'name'))) ?></p>
                        <img src="<?= htmlspecialchars(json_decode($listing['image'], true)['url']) ?>" alt="<?= htmlspecialchars(json_decode($listing['image'], true)['caption']) ?>" style="max-width: 200px;">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($events)): ?>
            <h2>Related Events</h2>
            <div class="related-events">
                <?php foreach ($events as $event): ?>
                    <div class="event">
                        <h3><?= htmlspecialchars($event['name']) ?></h3>
                        <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue_name']) ?></p>
                        <p><strong>Latitude:</strong> <?= htmlspecialchars($event['latitude']) ?> | <strong>Longitude:</strong> <?= htmlspecialchars($event['longitude']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($event['phone_numbers']['local']) ?></p>
                        <p><strong>Website:</strong> <a href="<?= htmlspecialchars($event['websites']['business']) ?>" target="_blank"><?= htmlspecialchars($event['websites']['business']) ?></a></p>
                        <p><strong>Event Dates:</strong></p>
                        <ul>
                            <?php 
                            $event_dates = json_decode($event['event_dates'], true);
                            foreach ($event_dates as $date) {
                                echo '<li>' . htmlspecialchars($date['name']) . ': ' . htmlspecialchars($date['start_date']) . ' - ' . htmlspecialchars($date['end_date']) . '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
