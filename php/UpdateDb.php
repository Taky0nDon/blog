<?php
$db = new SQLite3('jsontest.db');
$json_text = file_get_contents("/home/mike/extra-storage/code/blog/posts/json/post1template.json");
$post_parts = json_decode($json_text, true);
var_dump($post_parts);
$title =  $post_parts["title"];
$author = $post_parts["author"];
$date = $post_parts["date"];
$content = $post_parts["content"];
$db->exec("CREATE  TABLE IF NOT EXISTS post (
id INTEGER PRIMARY KEY,
ptitle TEXT NOT NULL,
pauthor TEXT NOT NULL,
pdate TEXT NOT NULL,
pcontent TEXT NOT NUll)"
);
$insertSql = $db->prepare('INSERT INTO post(ptitle, pauthor, pdate, pcontent) VALUES(:title,
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
