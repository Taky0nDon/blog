<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:1337");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
$post = array(
            "title" => "",
            "author" => "",
            "date" => "",
            "content" => "",
);

$validPostIds = array(
    "Thoughts on Nietzsche's 'The Birth of Tragedy'",
    "Thoughts on The Birth of This Blog"
    );

$request = json_decode(file_get_contents("php://input"), true);
$db = new SQLite3("../data/posts.db");

$postTitle = trim($request['id']);
file_put_contents("php://stdout", "received: {$postTitle}\n");

if (in_array($postTitle, $validPostIds, true)) {
    echo '{"error": "Invalid post title."}';
    exit;
}

$statement = $db->prepare("SELECT * FROM post where title=:postid");
$statement->bindParam(":postid", $postTitle);

file_put_contents('php://stdout', "{$statement->getSQL($expand=true)}\n");
$res = $statement->execute();

if (!$res) {
    echo "{'error':'500', 'message':'No post found with id {$postid}'}";
    exit;
} 

$post = $res->fetchArray(SQLITE3_ASSOC);
$postJson = json_encode($post);
file_put_contents("php://stdout", "json: $postJson\n");
echo($postJson);
?>
