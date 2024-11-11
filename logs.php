<?php
header('Content-Type: application/json');

function loadLogs() {
    if (!file_exists('logs.xml')) {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><logs></logs>');
        $xml->asXML('logs.xml');
    }
    return simplexml_load_file('logs.xml');
}

function saveLogs($xml) {
    return $xml->asXML('logs.xml');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new log entry
    $xml = loadLogs();
    
    $log = $xml->addChild('log');
    $log->addChild('action', htmlspecialchars($_POST['action']));
    $log->addChild('title', htmlspecialchars($_POST['title']));
    $log->addChild('timestamp', date('c')); // ISO 8601 format
    
    if (saveLogs($xml)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving log']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve logs
    $xml = loadLogs();
    $logs = [];
    
    foreach ($xml->log as $log) {
        $logs[] = [
            'action' => (string)$log->action,
            'title' => (string)$log->title,
            'timestamp' => (string)$log->timestamp
        ];
    }
    
    // Sort logs by timestamp in descending order
    usort($logs, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    
    echo json_encode($logs);
}
?>