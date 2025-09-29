<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$post = array(
            "title" => "",
            "author" => "",
            "date" => "",
            "content" => "",
);

$dbPath = getenv("SQLITE_DIR")."posts.db";
$db = new SQLite3($dbPath);
error_log("Db path: ${dbPath}\n");
$getTitlesSql = "SELECT title FROM post";
$getTitlesResult = $db->prepare($getTitlesSql)->execute();
$validTitles = $getTitlesResult->fetchArray(SQLITE3_NUM);

$request = file_get_contents("php://input");

if (!request) {
    echo '{"status": "400", "error": "Request body is empty!"}';
    exit;
}

$requestArray = json_decode($request, true);
$postTitle = trim($requestArray['id']);

if (!in_array($postTitle, $validTitles, false)) {
    echo '{"status": "400", "error": "No post exists in database with title ${postTitle}!"}';
    exit;
}

$statementGetPost = $db->prepare("SELECT * FROM post where title=:postid");
$statementGetPost->bindParam(":postid", $postTitle);
error_log("Searching d for ${postTitle}");

$res = $statementGetPost->execute();

if (!$res) {
    echo "{'error':'500', 'message':'No post found with id {$postTitle}'}";
    exit;
} 

$post = $res->fetchArray(SQLITE3_ASSOC);
$postJson = json_encode($post);
echo($postJson);
?>
