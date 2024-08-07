<?php

require_once '../../vendor/autoload.php';

use Faker\Factory;
use Ramsey\Uuid\Uuid;

class DatabaseManager {
    protected $pdo;
    protected $faker;

    public function __construct($host = 'database', $db = 'mydatabase', $user = 'lando', $pass = 'lando') {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->faker = Factory::create();
    }

    protected function getRandomHours() {
        $daysOfWeek = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $hours = [];
        foreach ($daysOfWeek as $day) {
            $isOpen = rand(0, 1); // Randomly decide if the listing is open on this day
            if ($isOpen) {
                $openAt = $this->faker->time($format = 'H:i', $max = '12:00');
                $closeAt = $this->faker->time($format = 'H:i', $min = $openAt);
                $hours[] = [
                    'dayOfWeek' => $day,
                    'openAt' => $openAt,
                    'closeAt' => $closeAt,
                    'allDay' => false,
                    'freetext' => ''
                ];
            }
        }
        return $hours;
    }

    protected function getRandomTaxonomy() {
        $taxonomy = [
            [
                'id' => '1299',
                'name' => 'Craft Beverage',
                'description' => '',
                'categories' => [
                    ['id' => '125', 'name' => 'Unique Venues']
                ]
            ],
            [
                'id' => '1298',
                'name' => 'Outdoor Setting',
                'description' => '',
                'categories' => [
                    ['id' => '125', 'name' => 'Unique Venues']
                ]
            ],
            [
                'id' => '1302',
                'name' => 'Mountain Views',
                'description' => '',
                'categories' => [
                    ['id' => '125', 'name' => 'Unique Venues']
                ]
            ],
            [
                'id' => '1303',
                'name' => 'Historical Site',
                'description' => '',
                'categories' => [
                    ['id' => '126', 'name' => 'Landmarks']
                ]
            ],
            [
                'id' => '1304',
                'name' => 'Haunted Place',
                'description' => '',
                'categories' => [
                    ['id' => '127', 'name' => 'Spooky']
                ]
            ],
            [
                'id' => '1305',
                'name' => 'Magical Forest',
                'description' => '',
                'categories' => [
                    ['id' => '128', 'name' => 'Nature']
                ]
            ],
            [
                'id' => '1306',
                'name' => 'Royal Castle',
                'description' => '',
                'categories' => [
                    ['id' => '129', 'name' => 'Architecture']
                ]
            ],
            [
                'id' => '1307',
                'name' => 'Dungeon',
                'description' => '',
                'categories' => [
                    ['id' => '130', 'name' => 'Adventure']
                ]
            ],
            [
                'id' => '1308',
                'name' => 'Wizard Tower',
                'description' => '',
                'categories' => [
                    ['id' => '131', 'name' => 'Mystical']
                ]
            ]
        ];

        $randomKeys = array_rand($taxonomy, mt_rand(1, 3));
        $randomTaxonomy = array_map(function($key) use ($taxonomy) {
            return $taxonomy[$key];
        }, (array) $randomKeys);

        return $randomTaxonomy;
    }

    public function generateDnDPhoneNumber() {
        return $this->faker->phoneNumber;
    }

    public function generateDnDStreetAddress() {
        $streets = [
            'Mystic Way', 'Dragonfire Road', 'Enchanted Blvd', 
            'Goblins Hollow', 'Elfstone Ave', 'Wizards Walk', 
            'Knight’s Path', 'Mermaid’s Lane', 'Giant’s Trail',
            'Phoenix Ave', 'Shadow Lane', 'Whispering Way',
            'Moonlit Drive', 'Cursed Blvd', 'Ebon Road',
            'Silver St', 'Crimson Ave', 'Arcane Drive',
            'Dragon’s Den Blvd', 'Mystic Grove Rd', 'Howling Wolf Lane',
            'Sapphire St', 'Golden Lion Road', 'Wandering Minstrel Ave',
            'Hidden Dagger St', 'Silent Sentinel Blvd', 'Dwarven Anvil Drive',
            'Enchanted Glade Way', 'Frosty Mug Lane', 'Noble’s Nook Road',
            'Roaring Dragon St', 'Celestial Lantern Blvd', 'Enchanted Flagon Ave',
            'Glowing Gem Road', 'Iron Horse Drive', 'Royal Unicorn St',
            'Spectral Raven Blvd', 'Thirsty Troll Ave', 'Whispering Pines Road',
            'Ancient Tome Drive', 'Fiery Phoenix St', 'Gleaming Goblet Blvd',
            'Moonlit Owl Ave', 'Noble Steed Road', 'Serpent’s Fang St',
            'Starlit Spire Blvd', 'Verdant Vale Ave', 'Wandering Wizard Road',
            'Mystic Fountain Drive', 'Shadowy Shroud St'
        ];
        return $this->faker->buildingNumber . ' ' . $this->faker->randomElement($streets);
    }

    public function getListings() {
        return $this->pdo->query('SELECT id, name FROM listings')->fetchAll();
    }

    public function getDeals() {
        return $this->pdo->query('SELECT id, title FROM deals')->fetchAll();
    }
}
