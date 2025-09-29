<?php
function _log($message) {
    file_put_contents("php.log", date(DATE_ATOM, time()).": ${message}\n");
}

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

_log("File accessed");
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
_log(join(", ", $validTitles));

$request = file_get_contents("php://input");

if ($request){
    _log("received request: {$request}\n");
} else {
    _log("received request: null!");
}

$requestArray = json_decode($request, true);
$postTitle = trim($requestArray['id']);
_log("Title: ${postTitle}");
_log(implode("::", $validTitles));

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
_log("json: $postJson\n");
echo($postJson);
?>
