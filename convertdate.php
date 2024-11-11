<?php
$xml = simplexml_load_file('articles.xml');

foreach ($xml->article as $article) {
    // Convert existing date to ISO 8601 format
    $oldDate = (string)$article->date;
    $timestamp = strtotime($oldDate);
    if ($timestamp) {
        $article->date = date('c', $timestamp);
    }
}

$xml->asXML('articles.xml');
echo "Dates converted successfully";
?>