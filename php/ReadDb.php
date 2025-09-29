<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: writethyself.net");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

echo "hello";
$post = array(
            "title" => "",
            "author" => "",
            "date" => "",
            "content" => "",
);
$SQLITE3_DIR = getenv("SQLITE_DIR")."/data/posts.db";
$db = new SQLite3($SQLITE3_DIR);
$getTitlesSql = "SELECT title FROM post";
$getTitlesResult = $db->prepare($getTitlesSql)->execute();
$validTitles = $getTitlesResult->fetchArray(SQLITE3_NUM);

$request = file_get_contents("php://input");

$requestArray = json_decode($request, true);
$postTitle = trim($requestArray['id']);

if (!in_array($postTitle, $validTitles, false)) {
    echo '{"status": "500", "error": "Invalid post title."}';
    exit;
}

$statementGetPost = $db->prepare("SELECT * FROM post where title=:postid");
$statementGetPost->bindParam(":postid", $postTitle);

file_put_contents('php://stdout', "{$statementGetPost->getSQL($expand=true)}\n");
$res = $statementGetPost->execute();

if (!$res) {
    echo "{'error':'500', 'message':'No post found with id {$postTitle}'}";
    exit;
} 

$post = $res->fetchArray(SQLITE3_ASSOC);
$postJson = json_encode($post);
echo($postJson);
?>
