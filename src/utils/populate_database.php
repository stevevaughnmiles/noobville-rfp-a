<?php

require_once '../../vendor/autoload.php';
require_once 'DatabaseManager.php';
require_once 'populate_listings.php';
require_once 'populate_deals.php';
require_once 'populate_articles.php';
require_once 'populate_events.php';


// Listings
$listingsManager = new ListingsManager('database', 'mydatabase', 'lando', 'lando');
$listingsManager->createListingsTable();
$listingsManager->createRandomListings(50);

// Deals
$dealsManager = new DealsManager('database', 'mydatabase', 'lando', 'lando');
$dealsManager->createDealsTable();
$dealsManager->createRandomDeals(20);

// Events
$eventsManager = new EventsManager('database', 'mydatabase', 'lando', 'lando');
$eventsManager->createEventsTable();
$eventsManager->createRandomEvents(20);

// Articles
$articlesManager = new ArticlesManager('database', 'mydatabase', 'lando', 'lando');
$articlesManager->createArticlesTable();
$articlesManager->createRandomArticles(20);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Population Complete</title>
    <link rel="stylesheet" href="css/internal.css">
</head>
<body>
    <div class="notification">
        <h1>Database Population Complete</h1>
        <p>Listings, Deals, Articles, and Events have been populated successfully.</p>
        <a href="/">Back to Homepage</a>
    </div>
</body>
</html>