<?php
require '../vendor/autoload.php';
require '../vendor/erusev/parsedown/Parsedown.php';

// Database connection
$dsn = 'mysql:host=database;dbname=mydatabase;charset=utf8mb4';
$user = 'lando';
$password = 'lando';

function checkTableExists($pdo, $tableName) {
    try {
        $result = $pdo->query("SELECT 1 FROM $tableName LIMIT 1");
    } catch (Exception $e) {
        return false;
    }
    return $result !== false;
}

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $listingsTableExists = checkTableExists($pdo, 'listings');
    $dealsTableExists = checkTableExists($pdo, 'deals');
    $articlesTableExists = checkTableExists($pdo, 'articles');
    $eventsTableExists = checkTableExists($pdo, 'events');

    if (!$listingsTableExists || !$dealsTableExists || !$articlesTableExists || !$eventsTableExists) {
        $readmeContents = file_get_contents('../readme.md');
        $parsedown = new Parsedown();
        $readmeHtml = $parsedown->text($readmeContents);
    } else {
        $listingsSchema = $pdo->query('DESCRIBE listings')->fetchAll();
        $dealsSchema = $pdo->query('DESCRIBE deals')->fetchAll();
        $articlesSchema = $pdo->query('DESCRIBE articles')->fetchAll();
        $eventsSchema = $pdo->query('DESCRIBE events')->fetchAll();
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
    <title>Database Schema and RFP</title>
    <link rel="stylesheet" href="css/internal.css">
</head>
<body>
    <?php if ($listingsTableExists && $dealsTableExists && $articlesTableExists && $eventsTableExists): ?>
        <div class="links">
            <a class="button" href="rfps/rfp-A.php">Review RFP A</a>
            <a class="button" href="rfps/rfp-B.php">Review RFP B</a>
            <a class="button" href="rfps/rfp-C.php">Review RFP C</a>
            <a class="button search-button" href="search.php">Search Listings, Deals, Articles, and Events</a>
            <a class="button search-button" href="calendar.php">Calendar</a>
        </div>

        <h1>Database Schema</h1>
        
        <h2>Listings Schema</h2>
        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listingsSchema as $column): ?>
                    <tr>
                        <td><?= htmlspecialchars($column['Field']) ?></td>
                        <td><?= htmlspecialchars($column['Type']) ?></td>
                        <td><?= htmlspecialchars($column['Null']) ?></td>
                        <td><?= htmlspecialchars($column['Key']) ?></td>
                        <td><?= htmlspecialchars($column['Default']) ?></td>
                        <td><?= htmlspecialchars($column['Extra']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Deals Schema</h2>
        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dealsSchema as $column): ?>
                    <tr>
                        <td><?= htmlspecialchars($column['Field']) ?></td>
                        <td><?= htmlspecialchars($column['Type']) ?></td>
                        <td><?= htmlspecialchars($column['Null']) ?></td>
                        <td><?= htmlspecialchars($column['Key']) ?></td>
                        <td><?= htmlspecialchars($column['Default']) ?></td>
                        <td><?= htmlspecialchars($column['Extra']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Articles Schema</h2>
        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articlesSchema as $column): ?>
                    <tr>
                        <td><?= htmlspecialchars($column['Field']) ?></td>
                        <td><?= htmlspecialchars($column['Type']) ?></td>
                        <td><?= htmlspecialchars($column['Null']) ?></td>
                        <td><?= htmlspecialchars($column['Key']) ?></td>
                        <td><?= htmlspecialchars($column['Default']) ?></td>
                        <td><?= htmlspecialchars($column['Extra']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Events Schema</h2>
        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventsSchema as $column): ?>
                    <tr>
                        <td><?= htmlspecialchars($column['Field']) ?></td>
                        <td><?= htmlspecialchars($column['Type']) ?></td>
                        <td><?= htmlspecialchars($column['Null']) ?></td>
                        <td><?= htmlspecialchars($column['Key']) ?></td>
                        <td><?= htmlspecialchars($column['Default']) ?></td>
                        <td><?= htmlspecialchars($column['Extra']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="markdown-content">
            <?= $readmeHtml ?>
        </div>
    <?php endif; ?>
</body>
</html>
