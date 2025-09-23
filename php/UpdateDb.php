<?php
function connectToDb($dbPath): SQLite3 {
    return new SQLite3($dbPath);
}

function getPostArray($jsonPath){
    $json_text = file_get_contents($jsonPath);
    $post_parts = json_decode($json_text, true);
    return $post_parts;
}


$pathToJson = "/home/mike/extra-storage/code/blog/posts/json/Thoughts on Nietzsche's 'The Birth of Tragedy'.json";
$post = getPostArray($pathToJson);

$title =  $post["title"];
$author = $post["author"];
$date = $post["date"];
$content = $post["content"];

$db = connectToDb("../data/posts.db");
$insertSql = $db->prepare('INSERT INTO post(title, author, date, content) VALUES(:title,
                :author,
                :date,
                :content
                )');
$insertSql->bindParam(':title', $title);
$insertSql->bindParam(':author', $author);
$insertSql->bindParam(':date', $date);
$insertSql->bindParam(':content', $content);
$insertSql->execute();
    
?>
