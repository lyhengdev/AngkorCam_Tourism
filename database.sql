-- =====================================================
-- AngkorCam Tourism - Professional Database Schema
-- Clean, Normalized, and Optimized
-- =====================================================

CREATE DATABASE IF NOT EXISTS angkorcam_pro 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE angkorcam_pro;

-- Drop tables in correct order
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS tours;
DROP TABLE IF EXISTS users;

-- =====================================================
-- Users Table
-- =====================================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tours Table
-- =====================================================
CREATE TABLE tours (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    location VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'Cultural',
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in days',
    description TEXT,
    highlights TEXT COMMENT 'What makes this tour special',
    included TEXT COMMENT 'What is included',
    excluded TEXT COMMENT 'What is not included',
    image VARCHAR(255),
    available_seats INT DEFAULT 15,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_location (location),
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Bookings Table
-- =====================================================
CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(50) UNIQUE NOT NULL,
    tour_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    travelers INT NOT NULL DEFAULT 1,
    booking_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    
    INDEX idx_booking_code (booking_code),
    INDEX idx_tour (tour_id),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_booking_date (booking_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Sample Data - Admin & Demo Content
-- =====================================================

-- Admin User (password: admin123)
INSERT INTO users (name, email, password, role, phone) VALUES
('Admin User', 'admin@angkorcam.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '+855 12 345 678'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', '+855 98 765 432');

-- Sample Tours
INSERT INTO tours (title, slug, location, category, price, duration, description, highlights, included, excluded, image, available_seats) VALUES
(
    'Angkor Wat Sunrise & Bayon Faces',
    'angkor-wat-sunrise-bayon',
    'Siem Reap',
    'Cultural',
    48.00,
    1,
    'Start before dawn to watch sunrise at Angkor Wat, then visit Bayon and its iconic stone faces in Angkor Thom.',
    'Angkor Wat sunrise|Bayon stone faces|Angkor Thom South Gate|Guided temple walk|Photo stops',
    'Hotel pickup|Licensed guide|Angkor pass|Cold water',
    'Meals|Personal expenses|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Sunrise_at_Angkor_Wat_Cambodia.jpg?width=1600',
    15
),
(
    'Ta Prohm Jungle Temple',
    'ta-prohm-jungle-temple',
    'Siem Reap',
    'Cultural',
    42.00,
    1,
    'Explore Ta Prohms tree-covered ruins and nearby quiet temples, with time for photography and stories.',
    'Ta Prohm roots|Hidden courtyards|Temple lore|Photo time|Small group',
    'Hotel pickup|Guide|Angkor pass|Water',
    'Meals|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Ta_Prohm_(I).jpg?width=1600',
    12
),
(
    'Preah Khan & Angkor Thom Gate',
    'preah-khan-angkor-thom',
    'Siem Reap',
    'Cultural',
    55.00,
    1,
    'Visit the Angkor Thom South Gate and wander the atmospheric galleries of Preah Khan with fewer crowds.',
    'Angkor Thom gate|Preah Khan temple|Forest corridors|Storytelling guide|Photo breaks',
    'Transport|Guide|Angkor pass|Water',
    'Meals|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Preah_Khan_temple_at_Angkor,_Cambodia.jpg?width=1600',
    14
),
(
    'Tonle Sap Floating Village & Sunset',
    'tonle-sap-floating-village',
    'Siem Reap',
    'Nature',
    40.00,
    1,
    'Cruise Tonle Sap to see floating homes and fisheries, with sunset views over the lake.',
    'Boat cruise|Floating village|Local life|Sunset on the lake|Cultural insight',
    'Boat ride|Guide|Transfers|Water',
    'Meals|Village donations|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Tonl%C3%A9_Sap_Floating_Village_(6202547090).jpg?width=1600',
    10
),
(
    'Phnom Penh Royal Palace & Riverside',
    'phnom-penh-royal-palace',
    'Phnom Penh',
    'Cultural',
    38.00,
    1,
    'Walk the Royal Palace and Silver Pagoda, then stroll the riverside promenade and historic streets.',
    'Royal Palace|Silver Pagoda|Riverside walk|Colonial architecture|Market stop',
    'Guide|Entrance fees|Water',
    'Meals|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Royal_Palace,_Phnom_Penh_Cambodia_1.jpg?width=1600',
    12
),
(
    'Battambang Bamboo Train & Countryside',
    'battambang-bamboo-train',
    'Battambang',
    'Adventure',
    45.00,
    1,
    'Ride the famous bamboo train and visit countryside villages, rice fields, and artisan workshops.',
    'Bamboo train ride|Village stops|Rice fields|Local crafts|Snack tasting',
    'Transport|Guide|Bamboo train|Water',
    'Meals|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Bamboo_train_Battambang.jpg?width=1600',
    12
),
(
    'Kampot & Kep Coastal Escape',
    'kampot-kep-coastal-escape',
    'Kampot',
    'Beach',
    120.00,
    2,
    'Two-day coastal getaway combining Kampots riverside charm, pepper farms, and Keps seafood scene.',
    'Kampot river cruise|Pepper farm visit|Kep crab market|Coastal views|Relaxed pace',
    '1 night hotel|Transport|Guide|Breakfast',
    'Lunches and dinners|Drinks|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Kep_Crab_Market.jpg?width=1600',
    8
),
(
    'Koh Rong Island Escape',
    'koh-rong-island-escape',
    'Sihanoukville',
    'Beach',
    180.00,
    3,
    'Spend three days on Koh Rong with beach time, optional snorkeling, and quiet island nights.',
    'White-sand beaches|Snorkel options|Island sunset|Free time|Boat transfers',
    'Round-trip boat|2 nights stay|Breakfast|Guide support',
    'Lunches and dinners|Snorkel gear|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Koh_Rong_-_Cambodia_(50925116073).jpg?width=1600',
    10
),
(
    'Kratie Mekong Dolphin Cruise',
    'kratie-mekong-dolphins',
    'Kratie',
    'Nature',
    65.00,
    1,
    'Cruise the Mekong near Kratie to spot rare Irrawaddy dolphins and learn about river life.',
    'Mekong cruise|Dolphin spotting|River villages|Local guide|Scenic views',
    'Boat cruise|Guide|Transfers|Water',
    'Meals|Tips',
    'https://commons.wikimedia.org/wiki/Special:FilePath/Irrawaddy_Dolphin.jpg?width=1600',
    10
);

-- Additional Tours (50)
INSERT INTO tours (title, slug, location, category, price, duration, description, highlights, included, excluded, image, available_seats) VALUES
('Banteay Srei Temple Artistry', 'banteay-srei-temple-artistry', 'Siem Reap', 'Cultural', '45.00', '1', 'Visit the pink sandstone temple of Banteay Srei and nearby countryside shrines.', 'Banteay Srei visit|Countryside temples|Photo stops|Local stories|Comfortable pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/014_Banteay_Srei_Temple_at_Dawn,_Cambodia.jpg?width=1600', '12'),
('Beng Mealea Jungle Ruins', 'beng-mealea-jungle-ruins', 'Siem Reap', 'Adventure', '55.00', '1', 'Walk through the overgrown ruins of Beng Mealea for a true jungle temple experience.', 'Jungle ruins|Remote temple|Guided walk|Photo time|Flexible route', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/07-Beng_Mealea-nX-1.jpg?width=1600', '12'),
('Phnom Kulen Sacred Mountain', 'phnom-kulen-sacred-mountain', 'Siem Reap', 'Nature', '60.00', '1', 'Explore Phnom Kulen\'s waterfalls, viewpoints, and sacred sites in a full-day nature escape.', 'Waterfalls|Scenic views|Sacred sites|Forest air|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Phnom_Kulen,_Cambodia_(2212211760).jpg?width=1600', '12'),
('Kbal Spean River Carvings', 'kbal-spean-river-carvings', 'Siem Reap', 'Nature', '35.00', '1', 'Hike to the River of a Thousand Lingas and enjoy a peaceful forest trail.', 'Forest hike|River carvings|Nature walk|Photo stops|Local guide', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Angkor-Kbal_Spean-04-Lingams-2007-gje.jpg?width=1600', '12'),
('Roluos Heritage Temples', 'roluos-heritage-temples', 'Siem Reap', 'Cultural', '38.00', '1', 'Visit the early Angkor temples of the Roluos group and learn their history.', 'Early Angkor sites|Heritage stories|Photo breaks|Quiet temples|Small group', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Angkor,_Roluos_Group,_Bakong_(6198838020).jpg?width=1600', '12'),
('Phnom Bakheng Sunset View', 'phnom-bakheng-sunset-view', 'Siem Reap', 'Cultural', '32.00', '1', 'Climb Phnom Bakheng for sunset views and explore nearby temple grounds.', 'Sunset viewpoint|Temple grounds|Guided walk|Photo time|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Angkor_Sunset.jpg?width=1600', '12'),
('Kampong Phluk Stilt Village', 'kampong-phluk-stilt-village', 'Siem Reap', 'Nature', '40.00', '1', 'See stilted homes and lake life in Kampong Phluk with a guided boat trip.', 'Stilt village|Boat ride|Local life|Lake views|Cultural insight', 'Boat|Guide|Transfers|Water', 'Meals|Village donations|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Kompong_Phluk.jpg?width=1600', '12'),
('Chong Kneas Lake Life', 'chong-kneas-lake-life', 'Siem Reap', 'Nature', '35.00', '1', 'Discover floating homes and fisheries at Chong Kneas on Tonle Sap.', 'Floating homes|Boat cruise|Lake sunset|Photo stops|Local guide', 'Boat|Guide|Transfers|Water', 'Meals|Village donations|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Chong_Khneas.jpg?width=1600', '12'),
('Preah Vihear Cliff Temple', 'preah-vihear-cliff-temple', 'Preah Vihear', 'Cultural', '85.00', '1', 'Travel to the dramatic cliff-top temple of Preah Vihear with panoramic views.', 'Cliff temple|Panoramic views|Heritage site|Photo time|Guided visit', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/03_Prasat_Preah_Vihear-nX-06518.jpg?width=1600', '12'),
('Koh Ker Pyramid Circuit', 'koh-ker-pyramid-circuit', 'Preah Vihear', 'Cultural', '75.00', '1', 'Explore the ancient pyramids and temples of Koh Ker with a local guide.', 'Prasat Thom pyramid|Remote temples|Guided walk|Photo breaks|History', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/04-Koh_Ker-nX-1.jpg?width=1600', '12'),
('Sambor Prei Kuk Heritage', 'sambor-prei-kuk-heritage', 'Kampong Thom', 'Cultural', '55.00', '1', 'Discover the pre-Angkorian temples of Sambor Prei Kuk in a peaceful forest setting.', 'UNESCO site|Ancient temples|Forest paths|Guided stories|Photo stops', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/One_of_many_temples_in_Sambor_Prei_Kuk.jpg?width=1600', '12'),
('Phnom Penh Royal Walk', 'phnom-penh-royal-walk', 'Phnom Penh', 'Cultural', '38.00', '1', 'Tour the Royal Palace area and colonial streets on a relaxed city walk.', 'Royal Palace area|Colonial streets|City highlights|Market stop|Local guide', 'Guide|Entrance fees|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Phnom_Penh,_Cambodia_-_panoramio.jpg?width=1600', '12'),
('Tuol Sleng & Choeung Ek History', 'tuol-sleng-choeung-ek-history', 'Phnom Penh', 'Cultural', '45.00', '1', 'Learn Cambodia\'s modern history with visits to Tuol Sleng and Choeung Ek.', 'Tuol Sleng|Choeung Ek|Historical context|Guided tour|Respectful visit', 'Transport|Guide|Entrance fees|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Tuol_Sleng_-_S21_-_Phnom_Penh_-_01.JPG?width=1600', '12'),
('Phnom Penh Night Market & Food', 'phnom-penh-night-market-food', 'Phnom Penh', 'Cultural', '32.00', '1', 'Experience the night market, street food, and riverfront lights.', 'Night market|Street food|Riverside walk|Local snacks|Photo time', 'Guide|Food tastings|Water', 'Extra drinks|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Phnom_Phen_Street_SCene_-_panoramio.jpg?width=1600', '12'),
('Silk Island Weaving Villages', 'silk-island-weaving-villages', 'Kandal', 'Cultural', '30.00', '1', 'Cycle or walk through Silk Island villages and meet local weaving families.', 'Silk weaving|Village visit|Ferry crossing|Craft demo|Local stories', 'Guide|Bike rental|Ferry|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Mekong_River,_Kandal_Province,_Cambodia.jpg?width=1600', '12'),
('Oudong Mountain Stupas', 'oudong-mountain-stupas', 'Kandal', 'Cultural', '40.00', '1', 'Climb Oudong to visit historic stupas and view the countryside.', 'Hilltop stupas|Countryside views|Guided walk|Photo stops|Local history', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Stupa_of_King_Sisowath_Monivong_in_Oudong.JPG?width=1600', '12'),
('Kampot Pepper Farm & Riverside', 'kampot-pepper-farm-riverside', 'Kampot', 'Cultural', '55.00', '1', 'Visit pepper farms and enjoy a riverside sunset in Kampot.', 'Pepper farm visit|Riverside sunset|Local tasting|Colonial town|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/River_in_Kampot.jpg?width=1600', '12'),
('Bokor National Park Mist Trail', 'bokor-national-park-mist-trail', 'Kampot', 'Nature', '60.00', '1', 'Journey into Bokor\'s cool highlands, forests, and viewpoints.', 'Highland views|Forest air|Historic sites|Photo stops|Easy hikes', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Scenic_View_from_Bokor_Hill_Station_-_Near_Kampot_-_Cambodia_-_01_(48529019777).jpg?width=1600', '12'),
('Kep Crab Market & Seaside', 'kep-crab-market-seaside', 'Kep', 'Beach', '45.00', '1', 'Taste fresh seafood at Kep crab market and unwind by the sea.', 'Crab market|Seaside views|Local flavors|Beach time|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Bringing_in_a_Seafood_Catch_-_Kep_-_Cambodia.JPG?width=1600', '12'),
('Rabbit Island Beach Day', 'rabbit-island-beach-day', 'Kep', 'Beach', '55.00', '1', 'Boat to Koh Tonsay for a simple island escape and beach time.', 'Island boat|Beach time|Seaside lunch|Swimming|Photo stops', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Boats_at_the_sandy_beach_of_the_Rabbit_Island_Koh_Tonsay_Cambodia.jpg?width=1600', '12'),
('Otres Beach Chill', 'otres-beach-chill', 'Sihanoukville', 'Beach', '35.00', '1', 'Spend the day on Otres Beach with calm water and seaside cafes.', 'Beach time|Sea breeze|Swimming|Free time|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Otres_Beach,_Sihanoukville.jpg?width=1600', '12'),
('Ream National Park Mangroves', 'ream-national-park-mangroves', 'Sihanoukville', 'Nature', '48.00', '1', 'Explore mangroves and coastal forests inside Ream National Park.', 'Mangrove boat|Nature walk|Birdlife|Coastal views|Local guide', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cambodia._Ream_National_Park._Coast.jpg?width=1600', '12'),
('Koh Rong Snorkel Adventure', 'koh-rong-snorkel-adventure', 'Sihanoukville', 'Beach', '85.00', '2', 'Two-day island stay on Koh Rong with snorkeling and beach time.', 'Snorkel spots|Island stay|Sunset views|Free time|Boat transfers', 'Boat|1 night stay|Guide|Breakfast', 'Lunches and dinners|Snorkel gear|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Long-beach-kog-rong-cambodia.jpg?width=1600', '12'),
('Koh Rong Samloem Lazy Bay', 'koh-rong-samloem-lazy-bay', 'Sihanoukville', 'Beach', '90.00', '2', 'Unplug on Koh Rong Samloem with calm beaches and quiet nights.', 'Lazy Beach|Island stay|Clear water|Sunset time|Boat transfers', 'Boat|1 night stay|Guide|Breakfast', 'Lunches and dinners|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Lazy_Beach,_Koh_Rong_Sanloem.jpg?width=1600', '12'),
('Sihanoukville Island Hopping', 'sihanoukville-island-hopping', 'Sihanoukville', 'Beach', '70.00', '1', 'Hop between nearby islands with time for swimming and snorkeling.', 'Island boat|Snorkel stops|Beach time|Picnic lunch|Clear water', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Sihanoukville_coastline.jpg?width=1600', '12'),
('Kratie Countryside Cycling', 'kratie-countryside-cycling', 'Kratie', 'Adventure', '45.00', '1', 'Cycle the Mekong countryside around Kratie and visit village markets.', 'Countryside ride|Village stops|Local markets|Scenic river|Easy pace', 'Bike rental|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Mekong_Islands_at_Kratie_-_panoramio.jpg?width=1600', '12'),
('Stung Treng 4000 Islands', 'stung-treng-4000-islands', 'Stung Treng', 'Nature', '65.00', '1', 'Cruise the Mekong to explore the islands and river scenery near Stung Treng.', 'River cruise|Island stops|Scenic views|Local stories|Photo time', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Countryside_outside_Stung_Treng_-_Cambodia_(48429007822).jpg?width=1600', '12'),
('Bousra Waterfall Escape', 'bousra-waterfall-escape', 'Mondulkiri', 'Nature', '75.00', '2', 'Visit Bousra Waterfall and enjoy cool highland air in Mondulkiri.', 'Waterfall visit|Highland views|Nature walk|Local villages|Relaxed pace', 'Transport|Guide|Water|1 night stay', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Bousra_Waterfall.jpg?width=1600', '12'),
('Sen Monorom Highlands', 'sen-monorom-highlands', 'Mondulkiri', 'Nature', '70.00', '2', 'Explore Sen Monorom and nearby viewpoints on a two-day highland trip.', 'Highland scenery|Coffee farms|Viewpoints|Local culture|Easy hikes', 'Transport|Guide|Water|1 night stay', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Forest_in_Mondulkiri.jpg?width=1600', '12'),
('Yeak Laom Crater Lake', 'yeak-laom-crater-lake', 'Ratanakiri', 'Nature', '70.00', '2', 'Relax by the volcanic lake of Yeak Laom and explore nearby forests.', 'Crater lake|Nature walk|Local culture|Forest air|Photo stops', 'Transport|Guide|Water|1 night stay', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Yeak_Laom_Lake_in_Paranoma_-Photo_2016-.jpg?width=1600', '12'),
('Virachey National Park Trek', 'virachey-national-park-trek', 'Ratanakiri', 'Adventure', '120.00', '3', 'Trek the remote trails of Virachey National Park with camping support.', 'Remote trek|Jungle paths|Camp nights|Wildlife signs|Adventure guide', 'Guide|Camping gear|Meals|Water', 'Personal gear|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Pra%C5%A1uma_na_sjeveroistoku_Kambod%C5%BEe.jpg?width=1600', '12'),
('Tatai River Kayak Day', 'tatai-river-kayak-day', 'Koh Kong', 'Nature', '55.00', '1', 'Kayak the Tatai River for a peaceful day among forests and waterways.', 'River kayak|Forest views|Relaxed pace|Photo stops|Local guide', 'Kayak|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Boat_In_Sunset_(205709807).jpeg?width=1600', '12'),
('Chi Phat Eco Trek', 'chi-phat-eco-trek', 'Koh Kong', 'Adventure', '95.00', '2', 'Explore Chi Phat\'s eco trails with local guides and community stays.', 'Eco trails|Community visit|Forest hikes|Local meals|Guided trek', 'Guide|1 night stay|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cambodia_jungle.jpg?width=1600', '12'),
('Cardamom Mountains Expedition', 'cardamom-mountains-expedition', 'Koh Kong', 'Adventure', '140.00', '3', 'A three-day adventure through the Cardamom Mountains and remote villages.', 'Mountain trek|Remote villages|Camp nights|Scenic viewpoints|Adventure guide', 'Guide|Camping gear|Meals|Water', 'Personal gear|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cardamom_sunset.jpg?width=1600', '12'),
('Kirirom Pine Forests', 'kirirom-pine-forests', 'Kampong Speu', 'Nature', '45.00', '1', 'Enjoy cool pine forests and viewpoints in Kirirom National Park.', 'Pine forests|Viewpoints|Nature walk|Picnic spots|Fresh air', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/1_Kirirom_nation_park_21-11-2010_-_panoramio.jpg?width=1600', '12'),
('Phnom Aural Summit Hike', 'phnom-aural-summit-hike', 'Kampong Speu', 'Adventure', '90.00', '2', 'Hike Cambodia\'s highest peak with a guided overnight trip.', 'Summit hike|Mountain trails|Overnight camp|Scenic views|Adventure guide', 'Guide|Camping gear|Meals|Water', 'Personal gear|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Gipfel_Phnom_Aoral.jpg?width=1600', '12'),
('Kampong Cham Bamboo Bridge', 'kampong-cham-bamboo-bridge', 'Kampong Cham', 'Cultural', '35.00', '1', 'Walk the bamboo bridge to Koh Pen and explore riverside villages.', 'Bamboo bridge|Riverside villages|Local culture|Photo stops|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Architectural_Detail_-_Koh_Paen_Island_-_Kampong_Cham_-_Cambodia_(48336138682).jpg?width=1600', '12'),
('Wat Nokor & Riverside', 'wat-nokor-riverside', 'Kampong Cham', 'Cultural', '35.00', '1', 'Visit Wat Nokor and enjoy a calm riverside afternoon in Kampong Cham.', 'Wat Nokor visit|Riverside walk|Local history|Photo time|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cambodge-Kampong_Cham-Vat_Nokor.JPG?width=1600', '12'),
('Takeo Phnom Da & Angkor Borei', 'takeo-phnom-da-angkor-borei', 'Takeo', 'Cultural', '55.00', '1', 'Explore Phnom Da and the ancient site of Angkor Borei with a local guide.', 'Phnom Da temple|Ancient ruins|Guided stories|Photo stops|Easy walk', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Phnom_Da_temple.JPG?width=1600', '12'),
('Banteay Chhmar Temple', 'banteay-chhmar-temple', 'Banteay Meanchey', 'Cultural', '65.00', '1', 'Discover the remote temple complex of Banteay Chhmar and its carvings.', 'Remote temple|Stone carvings|Guided visit|Quiet paths|Photo time', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Banteay_Chhmar_-_panoramio.jpg?width=1600', '12'),
('Anlong Veng Heritage Day', 'anlong-veng-heritage-day', 'Oddar Meanchey', 'Cultural', '60.00', '1', 'Visit historical sites around Anlong Veng and learn recent history.', 'Historical sites|Local stories|Countryside views|Photo stops|Guide', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Banteay_Meanchey_Province,_Cambodia.jpg?width=1600', '12'),
('Pailin Waterfall & Gem Markets', 'pailin-waterfall-gem-markets', 'Pailin', 'Nature', '50.00', '1', 'Enjoy a waterfall stop and explore Pailin\'s local markets.', 'Waterfall visit|Local markets|Countryside views|Photo time|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/View_from_Phnom_Yat._,_Pailin.jpg?width=1600', '12'),
('Battambang Art & Countryside', 'battambang-art-countryside', 'Battambang', 'Cultural', '45.00', '1', 'Discover Battambang\'s art scene, colonial streets, and nearby villages.', 'Art spaces|Colonial streets|Village visit|Local snacks|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Battambang-Kambodscha-Skyline.jpg?width=1600', '12'),
('Phnom Sampeau Bat Caves', 'phnom-sampeau-bat-caves', 'Battambang', 'Nature', '45.00', '1', 'Visit Phnom Sampeau and watch bats at dusk with scenic viewpoints.', 'Hilltop views|Bat cave|Sunset time|Local stories|Photo stops', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cave_on_Phnom_Sampeau.jpg?width=1600', '12'),
('Wat Banan Hill Temple', 'wat-banan-hill-temple', 'Battambang', 'Cultural', '45.00', '1', 'Climb Wat Banan\'s steps for panoramic views and temple history.', 'Hilltop temple|Panoramic views|Guided walk|Photo time|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Phnom_Banan_temple.jpg?width=1600', '12'),
('Kampong Luong Floating Village', 'kampong-luong-floating-village', 'Pursat', 'Nature', '45.00', '1', 'Cruise to Kampong Luong for a look at Tonle Sap floating life.', 'Floating village|Boat cruise|Local life|Lake views|Photo stops', 'Boat|Guide|Transfers|Water', 'Meals|Village donations|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Cottages_on_Lake_Tonl%C3%A9_Sap_in_Cambodia.jpg?width=1600', '12'),
('Prek Toal Bird Sanctuary', 'prek-toal-bird-sanctuary', 'Battambang', 'Nature', '65.00', '1', 'Take a boat to Prek Toal to see wetlands and seasonal birdlife.', 'Wetlands|Birdlife|Boat ride|Local guide|Photo time', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Fishing_nets_and_a_small_islet_on_the_lake_(36474422601).jpg?width=1600', '12'),
('Prey Veng Countryside Markets', 'prey-veng-countryside-markets', 'Prey Veng', 'Cultural', '35.00', '1', 'Explore local markets and village life in Prey Veng province.', 'Market visit|Village life|Local snacks|Photo stops|Easy pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Golden_rice_field_in_Prey_Veng_Province.jpg?width=1600', '12'),
('Svay Rieng Rice Fields & Pagodas', 'svay-rieng-rice-fields-pagodas', 'Svay Rieng', 'Cultural', '35.00', '1', 'Visit quiet pagodas and rice fields in Svay Rieng for a rural day.', 'Pagoda visits|Rice fields|Local villages|Photo time|Relaxed pace', 'Transport|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Chres_Commune,_Cambodia_-_panoramio_(1).jpg?width=1600', '12'),
('Peam Krasop Wildlife Sanctuary', 'peam-krasop-wildlife-sanctuary', 'Koh Kong', 'Nature', '55.00', '1', 'Cruise through mangroves at Peam Krasop and watch for wildlife.', 'Mangroves|Wildlife spotting|Boat cruise|Local guide|Photo stops', 'Boat|Guide|Water', 'Meals|Tips', 'https://commons.wikimedia.org/wiki/Special:FilePath/Mandrove_Trail_at_Peam_Krasop_Wildlife_Sanctuary.jpg?width=1600', '12');

-- Sample Bookings
INSERT INTO bookings (booking_code, tour_id, user_id, customer_name, customer_email, customer_phone, travelers, booking_date, total_price, status) VALUES
('AC-2026-001234', 1, 2, 'John Doe', 'john@example.com', '+855 98 765 432', 2, '2026-02-12', 96.00, 'confirmed'),
('AC-2026-001235', 3, 2, 'John Doe', 'john@example.com', '+855 98 765 432', 1, '2026-02-19', 55.00, 'pending');

COMMIT;


    DB_HOST = gateway01.ap-southeast-1.prod.aws.tidbcloud.com
    DB_PORT = 4000
    DB_USER = jURAATaAfqdgahg.root
    DB_PASS = your password
    DB_NAME = angkorcam_pro
