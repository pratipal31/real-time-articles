<?php
header('Content-Type: application/json');

function loadXML() {
    return simplexml_load_file('articles.xml');
}

function saveXML($xml) {
    return $xml->asXML('articles.xml');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['date'])) {
    $xml = loadXML();
    $targetTitle = $_POST['title'];
    $targetDate = $_POST['date'];
    $found = false;
    
    foreach ($xml->article as $article) {
        if ((string)$article->title === $targetTitle && (string)$article->date === $targetDate) {
            $dom = dom_import_simplexml($article);
            $dom->parentNode->removeChild($dom);
            $found = true;
            break;
        }
    }
    
    if ($found && saveXML($xml)) {
        echo json_encode(['success' => true, 'message' => 'Article deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => $found ? 'Error saving changes' : 'Article not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request - title and date required']);
}
?>