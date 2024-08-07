<?php

require_once '../../vendor/autoload.php';
require_once 'DatabaseManager.php';

use Ramsey\Uuid\Uuid;

class EventsManager extends DatabaseManager {
    public function createEventsTable() {
        $sqlEvents = "CREATE TABLE IF NOT EXISTS events (
            id CHAR(36) PRIMARY KEY,
            type VARCHAR(255),
            name VARCHAR(255),
            name_sort VARCHAR(255),
            latitude DOUBLE,
            longitude DOUBLE,
            email_addresses JSON,
            phone_numbers JSON,
            websites JSON,
            venue_name VARCHAR(255),
            event_dates JSON,
            venue JSON,
            taxonomy JSON
        )";
        $this->pdo->exec($sqlEvents);
    }

    public function populateEvents($data) {
        $sql = "INSERT INTO events (
            id, type, name, name_sort, latitude, longitude, email_addresses, phone_numbers, websites, venue_name, event_dates, venue, taxonomy
        ) VALUES (
            :id, :type, :name, :name_sort, :latitude, :longitude, :email_addresses, :phone_numbers, :websites, :venue_name, :event_dates, :venue, :taxonomy
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $data['id'],
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':name_sort' => $data['name_sort'],
            ':latitude' => $data['latitude'],
            ':longitude' => $data['longitude'],
            ':email_addresses' => json_encode($data['email_addresses']),
            ':phone_numbers' => json_encode($data['phone_numbers']),
            ':websites' => json_encode($data['websites']),
            ':venue_name' => $data['venue_name'],
            ':event_dates' => json_encode($data['event_dates']),
            ':venue' => json_encode($data['venue']),
            ':taxonomy' => json_encode($data['taxonomy'])
        ]);
    }

    public function createRandomEvents($count = 10) {
        $listings = $this->pdo->query('SELECT * FROM listings')->fetchAll();

        for ($i = 0; $i < $count; $i++) {
            $listing = $listings[array_rand($listings)];

            $eventDates = [];
            $startDate = new DateTime('2024-08-01');
            $startDate->modify('+' . mt_rand(0, 29) . ' days');
            for ($j = 0; $j < 3; $j++) {
                $start = (clone $startDate)->modify("+$j day")->format('Y-m-d\TH:i:s');
                $end = (clone $startDate)->modify("+$j day +8 hours")->format('Y-m-d\TH:i:s');
                $eventDates[] = [
                    'name' => $startDate->format('Y-m-d'),
                    'start_date' => $start,
                    'end_date' => $end,
                    'all_day' => false
                ];
            }

            $taxonomy = [
                [
                    'id' => 'adventure',
                    'name' => 'Adventure',
                    'description' => '',
                    'categories' => [
                        ['id' => '999', 'name' => 'Events']
                    ]
                ],
                [
                    'id' => 'festival',
                    'name' => 'Festival',
                    'description' => '',
                    'categories' => [
                        ['id' => '999', 'name' => 'Events']
                    ]
                ],
                [
                    'id' => 'workshop',
                    'name' => 'Workshop',
                    'description' => '',
                    'categories' => [
                        ['id' => '999', 'name' => 'Events']
                    ]
                ],
                [
                    'id' => 'competition',
                    'name' => 'Competition',
                    'description' => '',
                    'categories' => [
                        ['id' => '999', 'name' => 'Events']
                    ]
                ],
                [
                    'id' => 'gathering',
                    'name' => 'Gathering',
                    'description' => '',
                    'categories' => [
                        ['id' => '999', 'name' => 'Events']
                    ]
                ]
            ];

            $eventNames = [
                'Dragon Egg Hunt',
                'Wizard Duel Tournament',
                'Mystic Potion Brewing Class',
                'Enchanted Forest Hike',
                'Goblins and Ghouls Night',
                'Noobville Grand Feast',
                'Royal Jousting Competition',
                'Magical Creatures Expo',
                'Medieval Market Fair',
                'Heroes and Villains Costume Party',
                'Swordsmanship Training Camp',
                'Fairy Tale Storytelling',
                'Elven Archery Contest',
                'Dungeon Escape Challenge',
                'Potion Mixing Workshop',
                'Fantasy Art Exhibition',
                'Enchanted Music Festival',
                'Knight’s Jousting Tournament',
                'Sorcerer’s Spellcasting Showdown',
                'Trolls and Treasures Scavenger Hunt'
            ];

            $data = [
                'id' => Uuid::uuid4()->toString(),
                'type' => 'event',
                'name' => $this->faker->randomElement($eventNames),
                'name_sort' => $this->faker->randomElement($eventNames),
                'latitude' => $listing['latitude'],
                'longitude' => $listing['longitude'],
                'email_addresses' => [
                    'business' => $this->faker->email,
                    'booking' => $this->faker->email
                ],
                'phone_numbers' => [
                    'local' => $this->faker->phoneNumber,
                ],
                'websites' => [
                    'business' => $this->faker->url,
                ],
                'venue_name' => $listing['name'],
                'event_dates' => $eventDates,
                'venue' => [
                    'address_line_1' => $listing['address_line_1'],
                    'city' => $listing['city'],
                    'state' => $listing['state'],
                    'postcode' => $listing['postcode']
                ],
                'taxonomy' => $taxonomy
            ];

            $this->populateEvents($data);
        }
    }
}

// $eventsManager = new EventsManager('database', 'mydatabase', 'lando', 'lando');

// // Create events table
// $eventsManager->createEventsTable();

// // Populate events table with random data
// $eventsManager->createRandomEvents(10);

// echo "<br /> Events table created and populated with random data.";
