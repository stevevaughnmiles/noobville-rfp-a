<?php

require_once '../../vendor/autoload.php';
require_once 'DatabaseManager.php';

use Ramsey\Uuid\Uuid;

class DealsManager extends DatabaseManager {
    public function createDealsTable() {
        $sqlDeals = "CREATE TABLE IF NOT EXISTS deals (
            id CHAR(36) PRIMARY KEY,
            tracking_id CHAR(36),
            account_id INT,
            title VARCHAR(255),
            valid_start_date DATE,
            valid_end_date DATE,
            deal_text TEXT,
            deal_code VARCHAR(255),
            phone VARCHAR(255),
            email VARCHAR(255),
            website VARCHAR(255),
            listing_id CHAR(36),
            image JSON,
            category JSON,
            type VARCHAR(255)
        )";
        $this->pdo->exec($sqlDeals);
    }

    public function populateDeals($data) {
        $sql = "INSERT INTO deals (
            id, tracking_id, account_id, title, valid_start_date, valid_end_date, deal_text, deal_code, phone, email, website, listing_id, image, category, type
        ) VALUES (
            :id, :tracking_id, :account_id, :title, :valid_start_date, :valid_end_date, :deal_text, :deal_code, :phone, :email, :website, :listing_id, :image, :category, :type
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $data['id'],
            ':tracking_id' => $data['tracking_id'],
            ':account_id' => $data['account_id'],
            ':title' => $data['title'],
            ':valid_start_date' => $data['valid_start_date'],
            ':valid_end_date' => $data['valid_end_date'],
            ':deal_text' => $data['deal_text'],
            ':deal_code' => $data['deal_code'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':website' => $data['website'],
            ':listing_id' => $data['listing_id'],
            ':image' => json_encode($data['image']),
            ':category' => json_encode($data['category']),
            ':type' => $data['type']
        ]);
    }

    public function createRandomDeals($count = 20) {
        $listings = $this->pdo->query('SELECT id, name, latitude, longitude, address_line_1, city, state, postcode FROM listings')->fetchAll();
        $dealTitles = [
            '50% Off Magic Wands',
            'Free Ale with Every Meal',
            'Buy One Get One Free Sword Sharpening',
            'Discounted Potion Bundles',
            'Weekend Special at the Wizard Inn',
            '20% Off Armor Repairs',
            'Enchantments Half Price',
            'Dungeon Tour Discount',
            'Dragon Egg Sale',
            'Special Rates for Adventurers'
        ];

        for ($i = 0; $i < $count; $i++) {
            $listing = $listings[array_rand($listings)];
            $startDate = new DateTime('2024-08-01');
            $startDate->modify('+' . mt_rand(0, 29) . ' days');
            $endDate = (clone $startDate)->modify('+7 days');

            $data = [
                'id' => Uuid::uuid4()->toString(),
                'tracking_id' => Uuid::uuid4()->toString(),
                'account_id' => $this->faker->numberBetween(100, 999),
                'title' => $this->faker->randomElement($dealTitles),
                'valid_start_date' => $startDate->format('Y-m-d'),
                'valid_end_date' => $endDate->format('Y-m-d'),
                'deal_text' => $this->generateDealText(),
                'deal_code' => $this->faker->bothify('??##'),
                'phone' => $this->generateDnDPhoneNumber(),
                'email' => $this->faker->email,
                'website' => $this->faker->url,
                'listing_id' => $listing['id'],
                'image' => [
                    'url' => $this->faker->imageUrl(),
                    'caption' => $this->faker->sentence
                ],
                'category' => $this->getRandomCategories(),
                'type' => 'deal'
            ];
            $this->populateDeals($data);
        }
    }

    private function generateDealText() {
        $texts = [
            'Get an amazing discount on our premium magic wands this month.',
            'Enjoy a free ale with every meal at our renowned tavern.',
            'Take advantage of our buy one get one free sword sharpening service.',
            'Bundle up your potions and save with our discounted potion bundles.',
            'Spend a special weekend at the Wizard Inn with our exclusive rates.',
            'Receive 20% off on all armor repairs for a limited time.',
            'Our enchantments are now half price! Don’t miss out.',
            'Join us for a dungeon tour and enjoy a special discount.',
            'Dragon eggs are on sale! Grab yours before they’re gone.',
            'Adventurers, enjoy special rates at our various establishments.'
        ];

        return $this->faker->randomElement($texts);
    }

    private function getRandomCategories() {
        $categories = [
            ['id' => 1, 'name' => 'Lodging'],
            ['id' => 2, 'name' => 'Services'],
            ['id' => 3, 'name' => 'Discount'],
            ['id' => 4, 'name' => 'Adventure'],
            ['id' => 5, 'name' => 'Magic'],
            ['id' => 6, 'name' => 'Food & Drink'],
            ['id' => 7, 'name' => 'Weapons'],
            ['id' => 8, 'name' => 'Armor'],
            ['id' => 9, 'name' => 'Potions'],
            ['id' => 10, 'name' => 'Enchantments'],
            ['id' => 11, 'name' => 'Tours'],
            ['id' => 12, 'name' => 'Special Offers']
        ];

        return [$this->faker->randomElement($categories)];
    }
}

// $databaseManager = new DealsManager('database', 'mydatabase', 'lando', 'lando');

// // Create deals table
// $databaseManager->createDealsTable();

// // Populate deals table with random data
// $databaseManager->createRandomDeals(20);

// echo "<br /> Deals table created and populated with random data.";
