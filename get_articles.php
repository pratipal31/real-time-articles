<?php
header('Content-Type: application/json');

$xml = simplexml_load_file('articles.xml');

$articles = [];
foreach ($xml->article as $article) {
    // Create a unique identifier based on title and date
    $id = md5((string)$article->title . (string)$article->date);
    $articles[] = [
        'id' => $id,
        'title' => (string)$article->title,
        'content' => (string)$article->content,
        'date' => (string)$article->date
    ];
}

usort($articles, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

echo json_encode($articles);
?>  