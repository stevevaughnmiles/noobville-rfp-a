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

    $listingsStmt = $pdo->query('SELECT * FROM listings');
    $listings = $listingsStmt->fetchAll();

    $dealsStmt = $pdo->query('SELECT * FROM deals');
    $deals = $dealsStmt->fetchAll();

    $articlesStmt = $pdo->query('SELECT * FROM articles');
    $articles = $articlesStmt->fetchAll();

    $eventsStmt = $pdo->query('SELECT * FROM events');
    $events = $eventsStmt->fetchAll();

    // Aggregating distinct values for filters
    $categories = [];
    $taxonomy = [];
    $amenities = [];

    foreach ($listings as $listing) {
        $listingCategories = array_column(json_decode($listing['taxonomy'], true), 'name');
        $listingAmenities = array_column(json_decode($listing['amenities'], true), 'name');
        $categories = array_merge($categories, $listingCategories);
        $amenities = array_merge($amenities, $listingAmenities);
    }

    foreach ($deals as $deal) {
        $dealCategories = array_column(json_decode($deal['category'], true), 'name');
        $categories = array_merge($categories, $dealCategories);
    }

    foreach ($events as $event) {
        $eventTaxonomy = array_column(json_decode($event['taxonomy'], true), 'name');
        $taxonomy = array_merge($taxonomy, $eventTaxonomy);
    }

    // Remove duplicates and sort
    $categories = array_unique($categories);
    $taxonomy = array_unique($taxonomy);
    $amenities = array_unique($amenities);

    sort($categories);
    sort($taxonomy);
    sort($amenities);

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
    <title>Search Listings, Deals, Articles, and Events</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
</head>
<body>
    <div class="search-container">
        <h1>Search Listings, Deals, Articles, and Events</h1>
        <div class="filters">
            <label for="content-type">Filter by Content Type:</label>
            <select id="content-type" name="content-type">
                <option value="all">All</option>
                <option value="article">Articles</option>
                <option value="listing">Listings</option>
                <option value="deal">Deals</option>
                <option value="event">Events</option>
            </select>

            <label for="categories">Filter by Categories:</label>
            <select id="categories" name="categories" multiple>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="taxonomy">Filter by Taxonomy:</label>
            <select id="taxonomy" name="taxonomy" multiple>
                <?php foreach ($taxonomy as $tax): ?>
                    <option value="<?= htmlspecialchars($tax) ?>"><?= htmlspecialchars($tax) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="amenities">Filter by Amenities:</label>
            <select id="amenities" name="amenities" multiple>
                <?php foreach ($amenities as $amenity): ?>
                    <option value="<?= htmlspecialchars($amenity) ?>"><?= htmlspecialchars($amenity) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="date">Filter by Date:</label>
            <input type="date" id="date" name="date">

            <label for="time">Filter by Time:</label>
            <input type="time" id="time" name="time">
        </div>
        <div class="grid" id="grid">
            <?php foreach ($listings as $listing): ?>
                <div class="grid-item" data-type="listing" data-categories='<?= json_encode(array_column(json_decode($listing['taxonomy'], true), 'name')) ?>' data-taxonomy='<?= json_encode(array_column(json_decode($listing['taxonomy'], true), 'name')) ?>' data-amenities='<?= json_encode(array_column(json_decode($listing['amenities'], true), 'name')) ?>' data-dates='[]' data-times='[]'>
                    <h2><?= htmlspecialchars($listing['name']) ?></h2>
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

            <?php foreach ($deals as $deal): ?>
                <div class="grid-item" data-type="deal" data-categories='<?= json_encode(array_column(json_decode($deal['category'], true), 'name')) ?>' data-taxonomy='[]' data-amenities='[]' data-dates='[<?= json_encode($deal['valid_start_date']) ?>, <?= json_encode($deal['valid_end_date']) ?>]' data-times='[]'>
                    <h2><?= htmlspecialchars($deal['title']) ?></h2>
                    <p><strong>Valid From:</strong> <?= htmlspecialchars($deal['valid_start_date']) ?> to <?= htmlspecialchars($deal['valid_end_date']) ?></p>
                    <p><strong>Details:</strong> <?= htmlspecialchars($deal['deal_text']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($deal['phone']) ?></p>
                    <p><strong>Website:</strong> <a href="<?= htmlspecialchars($deal['website']) ?>" target="_blank"><?= htmlspecialchars($deal['website']) ?></a></p>
                    <img src="<?= htmlspecialchars(json_decode($deal['image'], true)['url']) ?>" alt="<?= htmlspecialchars(json_decode($deal['image'], true)['caption']) ?>" style="max-width: 200px;">
                </div>
            <?php endforeach; ?>

            <?php foreach ($articles as $article): ?>
                <div class="grid-item" data-type="article" data-categories='<?= json_encode(array_column(json_decode($article['category'], true), 'name')) ?>' data-taxonomy='[]' data-amenities='[]' data-listing-ids='<?= json_encode($article['listing_ids']) ?>' data-event-ids='<?= json_encode($article['event_ids']) ?>' data-dates='[]' data-times='[]'>
                    <h2><a href="view_article.php?id=<?= htmlspecialchars($article['id']) ?>"><?= htmlspecialchars($article['title']) ?></a></h2>
                    <p><?= htmlspecialchars(substr($article['content'], 0, 200)) ?>...</p>
                </div>
            <?php endforeach; ?>

            <?php foreach ($events as $event): ?>
                <div class="grid-item" data-type="event" data-categories='[]' data-taxonomy='<?= json_encode(array_column(json_decode($event['taxonomy'], true), 'name')) ?>' data-amenities='[]' data-dates='<?= json_encode(array_column(json_decode($event['event_dates'], true), 'start_date')) ?>' data-times='<?= json_encode(array_column(json_decode($event['event_dates'], true), 'start_date')) ?>'>
                    <h2><?= htmlspecialchars($event['name']) ?></h2>
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
    </div>
    <script src="js/scripts.js"></script>
</body>
</html>