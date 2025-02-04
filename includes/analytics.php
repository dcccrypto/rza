<?php
function trackKPI($type, $data) {
    global $conn;
    
    // KPI tracking for:
    // - Booking conversion rates
    // - User engagement metrics
    // - Educational resource usage
    // - Customer satisfaction scores
    // - Accessibility feature usage
    
    try {
        $stmt = prepare_stmt("INSERT INTO analytics (type, data, timestamp) VALUES (?, ?::jsonb, CURRENT_TIMESTAMP)");
        $json_data = json_encode($data);
        $stmt->execute([$type, $json_data]);
        
        return true;
    } catch (Exception $e) {
        error_log("Analytics tracking failed: " . $e->getMessage());
        return false;
    }
} 