-- Seed Data for Incredible India Tours
USE incredible_india_tours;

-- Insert Admin User (Password: admin123)
-- Hash generated using PHP password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@incredibleindia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert Destinations
INSERT INTO destinations (name, slug, description, image_url, featured) VALUES
('Goa', 'goa', 'Famous for its beaches, nightlife, and portuguese heritage.', 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?q=80&w=1000&auto=format&fit=crop', TRUE),
('Kerala', 'kerala', 'Gods Own Country, known for backwaters and greenery.', 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?q=80&w=1000&auto=format&fit=crop', TRUE),
('Rajasthan', 'rajasthan', 'Land of Kings, famous for palaces and deserts.', 'https://images.unsplash.com/photo-1477587458883-47145ed94245?q=80&w=1000&auto=format&fit=crop', TRUE),
('Himachal Pradesh', 'himachal-pradesh', 'Home to scenic mountain towns and resorts.', 'https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?q=80&w=1000&auto=format&fit=crop', TRUE),
('Kashmir', 'kashmir', 'Paradise on Earth, known for its scenic beauty.', 'https://images.unsplash.com/photo-1598091383021-15ddea10925d?q=80&w=1000&auto=format&fit=crop', FALSE),
('Ladakh', 'ladakh', 'Known for its stunning landscapes and monasteries.', 'https://images.unsplash.com/photo-1581793434119-984954e7acb8?q=80&w=1000&auto=format&fit=crop', TRUE),
('Agra', 'agra', 'Home of the Taj Mahal.', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=1000&auto=format&fit=crop', TRUE),
('Jaipur', 'jaipur', 'The Pink City, known for Hawa Mahal and forts.', 'https://images.unsplash.com/photo-1477587458883-47145ed94245?q=80&w=1000&auto=format&fit=crop', TRUE);

-- Insert Tours
INSERT INTO tours (destination_id, title, slug, description, price, duration, itinerary, inclusions, exclusions, images, featured) VALUES
(1, 'Goa Beach Party & Relax', 'goa-beach-party-relax', 'Enjoy 4 days of sun, sand, and parties in Goa.', 15000.00, '4 Days / 3 Nights', 
'[
    {"day": 1, "title": "Arrival in Goa", "details": "Check-in at hotel, evening at Calangute Beach."},
    {"day": 2, "title": "North Goa Tour", "details": "Visit Fort Aguada, Baga Beach, and Anjuna Flea Market."},
    {"day": 3, "title": "South Goa Tour", "details": "Visit Old Goa Churches, Mangueshi Temple, and Cruise."},
    {"day": 4, "title": "Departure", "details": "Check-out and transfer to airport."}
]', 
'["Hotel Stay", "Breakfast", "Sightseeing", "Airport Transfers"]', 
'["Lunch", "Dinner", "Personal Expenses"]', 
'["https://images.unsplash.com/photo-1512343879784-a960bf40e7f2"]', 
TRUE),

(2, 'Kerala Backwaters Bliss', 'kerala-backwaters-bliss', 'Experience the serene backwaters of Alleppey and Kumarakom.', 22000.00, '5 Days / 4 Nights',
'[
    {"day": 1, "title": "Arrival in Kochi", "details": "Transfer to Munnar, visit tea gardens."},
    {"day": 2, "title": "Munnar Sightseeing", "details": "Mattupetty Dam, Echo Point, and Eravikulam National Park."},
    {"day": 3, "title": "Thekkady", "details": "Drive to Thekkady, boat ride in Periyar Lake."},
    {"day": 4, "title": "Alleppey Houseboat", "details": "Stay in a traditional houseboat."},
    {"day": 5, "title": "Departure", "details": "Transfer to Kochi airport."}
]', 
'["Houseboat Stay", "Breakfast", "Dinner in Houseboat", "Transfers"]', 
'["Flight Tickets", "Personal Expenses"]', 
'["https://images.unsplash.com/photo-1602216056096-3b40cc0c9944"]', 
TRUE),

(3, 'Royal Rajasthan Tour', 'royal-rajasthan-tour', 'Explore the forts and palaces of Jaipur, Jodhpur, and Udaipur.', 35000.00, '7 Days / 6 Nights',
'[
    {"day": 1, "title": "Arrival in Jaipur", "details": "Visit City Palace and Jantar Mantar."},
    {"day": 2, "title": "Jaipur Sightseeing", "details": "Amber Fort, Hawa Mahal, and Jal Mahal."},
    {"day": 3, "title": "Drive to Jodhpur", "details": "Visit Mehrangarh Fort."},
    {"day": 4, "title": "Jodhpur to Udaipur", "details": "Enroute visit Ranakpur Jain Temple."},
    {"day": 5, "title": "Udaipur Sightseeing", "details": "City Palace, Lake Pichola boat ride."},
    {"day": 6, "title": "Leisure", "details": "Shopping in local markets."},
    {"day": 7, "title": "Departure", "details": "Transfer to Udaipur airport."}
]', 
'["Hotel Stay", "Daily Breakfast", "Sightseeing", "Guide"]', 
'["Entry Fees", "Lunch & Dinner"]', 
'["https://images.unsplash.com/photo-1477587458883-47145ed94245"]', 
TRUE);
