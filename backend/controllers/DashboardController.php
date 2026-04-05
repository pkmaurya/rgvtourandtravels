<?php
require_once __DIR__ . '/../models/Booking.php';

class DashboardController {
    private $db;
    private $booking;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->booking = new Booking($this->db);
    }

    public function getStats() {
        $stats = $this->booking->getStats();
        echo json_encode($stats);
    }

    public function getRecentBookings() {
        $stmt = $this->booking->getRecent();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format dates and times
        foreach($bookings as &$booking) {
            $date = new DateTime($booking['date']);
            $booking['date'] = $date->format('d/m/Y');
            $booking['time'] = $date->format('H:i');
        }

        echo json_encode(['data' => $bookings]);
    }
    
    // Mock Chart Data (can be implemented with real logic later)
    public function getChartData() {
        $days = isset($_GET['days']) ? (int)$_GET['days'] : 6; // Default to last 7 days (including today)
        $stmt = $this->booking->getDailyEarnings($days);
        $earnings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $labels = [];
        $data = [];
        
        // Map date => total
        $earningsMap = [];
        foreach($earnings as $row) {
             $earningsMap[$row['date']] = $row['total'];
        }

        // Generate last N days
        for ($i = $days; $i >= 0; $i--) {
             $date = date('Y-m-d', strtotime("-$i days"));
             $dayName = date('D', strtotime("-$i days"));
             
             $labels[] = $dayName;
             $data[] = isset($earningsMap[$date]) ? (float)$earningsMap[$date] : 0;
        }

        echo json_encode([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Earnings',
                    'data' => $data
                ]
            ]
        ]);
    }
}
?>
