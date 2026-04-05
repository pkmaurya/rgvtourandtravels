<?php
class ChatAgent {
    private $conn;
    private $table = 'chatbot_qa';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function processMessage($message) {
        $message = strtolower(trim($message));

        // 1. Check for basic greetings/intents
        if ($this->isGreeting($message)) {
            return [
                'answer' => "Hello! 👋 Welcome to RGV Tour and Travels. How can I assist you with your travel plans today?",
                'suggestions' => $this->getCategorySuggestions()
            ];
        }

        if ($this->isFarewell($message)) {
            return [
                'answer' => "Goodbye! Have a wonderful day. Feel free to return if you have more questions.",
                'suggestions' => []
            ];
        }

        // 2. Search Database for Keyword Matches
        $matches = $this->searchDatabase($message);

        if (!empty($matches)) {
            // If we found a direct match or very strong keyword association
            if (count($matches) === 1 && $matches[0]['score'] > 5) {
                return [
                    'answer' => $matches[0]['answer'],
                    'suggestions' => []
                ];
            }

            // Return top suggestions
            return [
                'answer' => "I found some information that might help:",
                'suggestions' => array_slice($matches, 0, 3)
            ];
        }

        // 3. Fallback
        return [
            'answer' => "I'm not sure I have the answer to that specific question. You can try asking about our 'Tours', 'Booking' process, or 'Support'.",
            'suggestions' => $this->getCategorySuggestions()
        ];
    }

    private function isGreeting($msg) {
        $greetings = ['hi', 'hello', 'hey', 'start', 'good morning', 'good afternoon', 'good evening'];
        return in_array($msg, $greetings);
    }

    private function isFarewell($msg) {
        $farewells = ['bye', 'goodbye', 'end', 'exit', 'quit', 'thanks', 'thank you'];
        return in_array($msg, $farewells);
    }

    private function searchDatabase($term) {
        // Fetch all active Q&A
        $query = 'SELECT * FROM ' . $this->table . ' WHERE is_active = 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $allQA = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];

        foreach ($allQA as $qa) {
            $score = 0;
            $q_lower = strtolower($qa['question']);
            $a_lower = strtolower($qa['answer']);
            $c_lower = strtolower($qa['category']);

            // Exact match in question
            if ($q_lower === $term) {
                $score += 100;
            }
            // Contains exact phrase
            elseif (strpos($q_lower, $term) !== false) {
                $score += 20;
            }
            // Keyword matching (naive)
            else {
                $words = explode(' ', $term);
                foreach ($words as $word) {
                    if (strlen($word) > 3) {
                        if (strpos($q_lower, $word) !== false) $score += 5;
                        if (strpos($a_lower, $word) !== false) $score += 2;
                        if (strpos($c_lower, $word) !== false) $score += 3;
                    }
                }
            }

            if ($score > 0) {
                $qa['score'] = $score;
                $results[] = $qa;
            }
        }

        // Sort by score DESC
        usort($results, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return $results;
    }

    private function getCategorySuggestions() {
        return [
            ['question' => 'Show me popular tours', 'category' => 'tour'],
            ['question' => 'How do I contact support?', 'category' => 'support'],
            ['question' => 'What is the cancellation policy?', 'category' => 'general']
        ];
    }
}
?>
