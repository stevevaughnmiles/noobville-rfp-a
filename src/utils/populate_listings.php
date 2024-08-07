<?php

require_once '../../vendor/autoload.php';
require_once 'DatabaseManager.php';

use Ramsey\Uuid\Uuid;

class ListingsManager extends DatabaseManager {
    public function createListingsTable() {
        $sqlListings = "CREATE TABLE IF NOT EXISTS listings (
            id CHAR(36) PRIMARY KEY,
            name VARCHAR(255),
            latitude DOUBLE,
            longitude DOUBLE,
            phone_local VARCHAR(255),
            hours JSON,
            address_line_1 VARCHAR(255),
            city VARCHAR(255),
            state VARCHAR(255),
            postcode VARCHAR(255),
            amenities JSON,
            image JSON,
            taxonomy JSON
        )";
        $this->pdo->exec($sqlListings);
    }

    public function populateListings($data) {
        $sql = "INSERT INTO listings (
            id, name, latitude, longitude, phone_local, hours, address_line_1, city, state, postcode, amenities, image, taxonomy
        ) VALUES (
            :id, :name, :latitude, :longitude, :phone_local, :hours, :address_line_1, :city, :state, :postcode, :amenities, :image, :taxonomy
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':latitude' => $data['latitude'],
            ':longitude' => $data['longitude'],
            ':phone_local' => $data['phone_local'],
            ':hours' => json_encode($data['hours']),
            ':address_line_1' => $data['address_line_1'],
            ':city' => $data['city'],
            ':state' => $data['state'],
            ':postcode' => $data['postcode'],
            ':amenities' => json_encode($data['amenities']),
            ':image' => json_encode($data['image']),
            ':taxonomy' => json_encode($data['taxonomy'])
        ]);
    }

    public function createRandomListings($count = 50) {
        $baseLatitude = 35.5951;
        $baseLongitude = -82.5515;
        $radius = 5 / 69; // 5 miles radius converted to degrees (approx. 69 miles per degree)

        for ($i = 0; $i < $count; $i++) {
            $angle = mt_rand(0, 360);
            $distance = sqrt(mt_rand(0, $radius * $radius));

            $latitude = $baseLatitude + $distance * cos(deg2rad($angle));
            $longitude = $baseLongitude + $distance * sin(deg2rad($angle));

            $data = [
                'id' => Uuid::uuid4()->toString(),
                'name' => $this->generateDnDName(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'phone_local' => $this->generateDnDPhoneNumber(),
                'hours' => $this->getRandomHours(),
                'address_line_1' => $this->generateDnDStreetAddress(),
                'city' => 'Noobtowne',
                'state' => 'NT',
                'postcode' => $this->faker->postcode,
                'amenities' => $this->getRandomAmenities(),
                'image' => [
                    'url' => $this->faker->imageUrl(),
                    'caption' => $this->faker->sentence
                ],
                'taxonomy' => $this->getRandomTaxonomy()
            ];
            $this->populateListings($data);
        }
    }

    private function generateDnDName() {
        $names = [
            'The Enchanted Oak', 'Dragon’s Breath Inn', 'The Mystic Cauldron', 
            'Goblin’s Gold Tavern', 'Elfstone Lodge', 'Wizard’s Tower', 
            'Knight’s Rest', 'Mermaid’s Lagoon', 'Giant’s Footpath Inn',
            'The Gilded Griffin', 'Phoenix Feather Tavern', 'Shadow’s End Pub',
            'The Whispering Willow', 'Moonlit Meadow Inn', 'The Cursed Chalice',
            'The Ebon Flask', 'The Silver Stag', 'The Crimson Lance',
            'The Arcane Alehouse', 'The Dragon’s Den', 'The Mystic Grove',
            'The Howling Wolf Inn', 'The Sapphire Shield', 'The Golden Lion',
            'The Wandering Minstrel', 'The Hidden Dagger', 'The Silent Sentinel',
            'The Dwarven Anvil', 'The Enchanted Glade', 'The Frosty Mug',
            'The Noble’s Nook', 'The Roaring Dragon', 'The Celestial Lantern',
            'The Enchanted Flagon', 'The Glowing Gem', 'The Iron Horse Tavern',
            'The Royal Unicorn', 'The Spectral Raven', 'The Thirsty Troll',
            'The Whispering Pines', 'The Ancient Tome', 'The Fiery Phoenix',
            'The Gleaming Goblet', 'The Moonlit Owl', 'The Noble Steed',
            'The Serpent’s Fang', 'The Starlit Spire', 'The Verdant Vale',
            'The Wandering Wizard', 'The Mystic Fountain', 'The Shadowy Shroud'
        ];
        return $this->faker->randomElement($names);
    }

    private function getRandomAmenities() {
        $amenities = [
            ['amenity_id' => '4', 'name' => 'Fireplace'],
            ['amenity_id' => '33', 'name' => 'Stables for Mounts'],
            ['amenity_id' => '39', 'name' => 'Enchanting Services'],
            ['amenity_id' => '51', 'name' => 'Potion Brewing Station'],
            ['amenity_id' => '107', 'name' => 'Blacksmithing Anvil'],
            ['amenity_id' => '196', 'name' => 'Outdoor Seating'],
            ['amenity_id' => '134', 'name' => 'Alchemy Lab'],
            ['amenity_id' => '144', 'name' => 'Free Parking for Carriages'],
            ['amenity_id' => '152', 'name' => 'Wheelchair Accessible'],
            ['amenity_id' => '153', 'name' => 'Dungeon Access'],
            ['amenity_id' => '160', 'name' => 'Public Restroom'],
            ['amenity_id' => '194', 'name' => 'Military Discount'],
            ['amenity_id' => '208', 'name' => 'Tavern'],
            ['amenity_id' => '209', 'name' => 'Library'],
            ['amenity_id' => '210', 'name' => 'Courtyard'],
            ['amenity_id' => '179', 'name' => 'Meeting Rooms'],
            ['amenity_id' => '182', 'name' => 'Banquet Hall'],
            ['amenity_id' => '184', 'name' => 'Max Capacity 200'],
            ['amenity_id' => '213', 'name' => 'Wedding Ceremony Area'],
            ['amenity_id' => '214', 'name' => 'Reception Area'],
            ['amenity_id' => '215', 'name' => 'Outdoor Event Space'],
            ['amenity_id' => '217', 'name' => 'Max Capacity 500'],
        ];

        $randomKeys = array_rand($amenities, mt_rand(3, 7));
        $randomAmenities = array_map(function($key) use ($amenities) {
            return $amenities[$key];
        }, (array) $randomKeys);

        return $randomAmenities;
    }
}

// $listingsManager = new ListingsManager('database', 'mydatabase', 'lando', 'lando');

// // Create listings table
// $listingsManager->createListingsTable();

// // Populate listings table with random data
// $listingsManager->createRandomListings(50);

// echo "<br /> Listings table created and populated with random data.";
