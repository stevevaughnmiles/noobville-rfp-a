<?php

require_once '../../vendor/autoload.php';
require_once 'DatabaseManager.php';

use Ramsey\Uuid\Uuid;
use Faker\Factory as FakerFactory;

class ArticlesManager extends DatabaseManager {

    public function __construct($host, $dbname, $user, $password) {
        parent::__construct($host, $dbname, $user, $password);
        $this->faker = FakerFactory::create();
    }

    public function createArticlesTable() {
        $sqlArticles = "CREATE TABLE IF NOT EXISTS articles (
            id CHAR(36) PRIMARY KEY,
            title VARCHAR(255),
            content TEXT,
            listing_ids JSON,
            event_ids JSON,
            deal_id CHAR(36),
            image JSON,
            category JSON,
            type VARCHAR(255)
        )";
        $this->pdo->exec($sqlArticles);
    }

    public function populateArticles($data) {
        $sql = "INSERT INTO articles (
            id, title, content, listing_ids, event_ids, deal_id, image, category, type
        ) VALUES (
            :id, :title, :content, :listing_ids, :event_ids, :deal_id, :image, :category, :type
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':listing_ids' => json_encode($data['listing_ids']),
            ':event_ids' => json_encode($data['event_ids']),
            ':deal_id' => $data['deal_id'],
            ':image' => json_encode($data['image']),
            ':category' => json_encode($data['category']),
            ':type' => $data['type']
        ]);
    }

    public function createRandomArticles($count = 10) {
        $listings = $this->pdo->query('SELECT id, name FROM listings')->fetchAll();
        $events = $this->pdo->query('SELECT id, name FROM events')->fetchAll();
        $deals = $this->pdo->query('SELECT id, title FROM deals')->fetchAll();

        for ($i = 0; $i < $count; $i++) {
            $numItems = $this->faker->numberBetween(5, 10);
            $content = '';

            $listingIds = [];
            $eventIds = [];

            for ($j = 1; $j <= $numItems; $j++) {
                $listing = $listings[array_rand($listings)];
                $event = $events[array_rand($events)];
                $deal = $deals[array_rand($deals)];
                $listingIds[] = $listing['id'];
                $eventIds[] = $event['id'];

                $content .= "<p><strong>Top $j:</strong> {$this->faker->sentence}</p>";
                $content .= "<p>Journey to <strong>{$listing['name']}</strong> and immerse yourself in an experience like no other. Nestled in the heart of Noobville, this location offers a unique blend of adventure and relaxation. {$listing['name']} is renowned for its enchanting atmosphere, perfect for both seasoned adventurers and new explorers alike.</p>";
                $content .= "<p>Don't miss out on the event <strong>{$event['name']}</strong> happening nearby. It's a perfect opportunity to make the most of your visit.</p>";
                $content .= "<p>While you're there, take advantage of the incredible deal: '<strong>{$deal['title']}</strong>'. This exclusive offer will enhance your visit, providing extra value and making your adventure even more memorable. Don't miss out on this fantastic opportunity available at <strong>{$listing['name']}</strong>.</p>";
            }

            $data = [
                'id' => Uuid::uuid4()->toString(),
                'title' => 'Top ' . $numItems . ' Things to Do in Noobville',
                'content' => $content,
                'listing_ids' => $listingIds,
                'event_ids' => $eventIds,
                'deal_id' => $deal['id'],
                'image' => [
                    'url' => $this->faker->imageUrl(),
                    'caption' => $this->faker->sentence
                ],
                'category' => $this->getRandomCategories(),
                'type' => 'article'
            ];
            $this->populateArticles($data);
        }
    }

    private function getRandomCategories() {
        $categories = [
            ['id' => 1, 'name' => 'Adventure'],
            ['id' => 2, 'name' => 'Magic'],
            ['id' => 3, 'name' => 'Historical'],
            ['id' => 4, 'name' => 'Relaxation'],
            ['id' => 5, 'name' => 'Family']
        ];

        return [$this->faker->randomElement($categories)];
    }
}

// $articlesManager = new ArticlesManager('database', 'mydatabase', 'lando', 'lando');

// // Create articles table
// $articlesManager->createArticlesTable();

// // Populate articles table with random data
// $articlesManager->createRandomArticles(10);

// echo "Articles table created and populated with random data.";
