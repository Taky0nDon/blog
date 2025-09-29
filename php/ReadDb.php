<?php
function _log($message) {
    file_put_contents("php://stdout", "${message}\n");
}

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

$db = new SQLite3("../data/posts.db");
$getTitlesSql = "SELECT title FROM post";
$getTitlesResult = $db->prepare($getTitlesSql)->execute();
$titlesArray = $getTitlesResult->fetchArray(SQLITE3_NUM);
var_dump($titlesArray);

$validPostIds = array(
    'Thoughts on Nietzsche\'s "The Birth of Tragedy"',
    "Thoughts on The Birth of This Blog"
    );

if (in_array($validPostIds[0], $titlesArray, false)) {
    echo "found it";
} else {
    echo "didnt find it.";
}
var_dump($validPostIds);
$request = file_get_contents("php://input");
if ($request){
    file_put_contents("php://stdout", "received request: {$request}\n");
} else {
    file_put_contents("php://stdout", "received request: null!");
}

$requestArray = json_decode($request, true);

$postTitle = trim($requestArray['id']);
_log("Title: ${postTitle}");
_log(implode("::", $validPostIds));

/*
if (!in_array($postTitle, $validPostIds, true)) {
    echo '{"error": "Invalid post title."}';
    exit;
}
*/

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
