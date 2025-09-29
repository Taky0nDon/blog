<?php
function removeUnsavoryCharacters(string $str): string {
    $charsToReplace = array([
        "'",
        '"'
    ]);
    return str_replace($charsToReplace, "", $str);
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

$dbPath = getenv("SQLITE_DIR")."posts.db";
$db = new SQLite3($dbPath);
error_log("Db path: ${dbPath}\n");
$getTitlesSql = "SELECT title FROM post";
$getTitlesResult = $db->prepare($getTitlesSql)->execute();
$validTitles = $getTitlesResult->fetchArray(SQLITE3_NUM);

$request = file_get_contents("php://input");

if (!$request) {
    echo '{"status": "400", "error": "Request body is empty!"}';
    exit;
}

$requestArray = json_decode($request, true);
$postTitle = removeUnsavoryCharacters(trim($requestArray['title']));

if (!in_array($db->escapeString($postTitle), $validTitles, false)) {
    echo json_encode([
        "status" => "400",
        "error" => "${postTitle} does not match any of".implode(", ", $validTitles)
    ]);
    exit;
}

$statementGetPost = $db->prepare('SELECT * FROM post where title=":postid"');
$statementGetPost->bindParam(":postid", $postTitle);

$res = $statementGetPost->execute();

if (!$res) {
    echo json_encode([
        "error"=>"500",
        "message"=>"No post found with id ${postTitle}."
    ]);
    exit;
} 

$post = $res->fetchArray(SQLITE3_ASSOC);
$postJson = json_encode($post);
echo($postJson);
?>
