<?php
header('Content-Type: application/json');

function loadXML() {
    return simplexml_load_file('articles.xml');
}

function saveXML($xml) {
    return $xml->asXML('articles.xml');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xmlFile = 'articles.xml';

    if (!is_writable($xmlFile)) {
        echo json_encode(['success' => false, 'message' => 'XML file is not writable.']);
        exit;
    }

    $xml = loadXML();
    
    $article = $xml->addChild('article');
    $article->addChild('title', htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
    $article->addChild('content', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'));
    // Use ISO 8601 format for better JavaScript compatibility
    $article->addChild('date', date('c'));

    if (saveXML($xml)) {
        echo json_encode(['success' => true, 'message' => 'Article added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving article to XML file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>