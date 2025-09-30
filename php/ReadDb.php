<?php
function removeUnsavoryCharacters(string $str): string {
    $charsToReplace = ["'", '"'];
    return str_replace($charsToReplace, "", $str);
}
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: writethyself.net");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$validTitles = array();
$post = array(
            "title" => "",
            "author" => "",
            "date" => "",
            "content" => "",
);

$dbPath = getenv("SQLITE_DIR")."posts.db";
$db = new SQLite3($dbPath);
$getTitlesSql = "SELECT title FROM post";
$getTitlesResult = $db->prepare($getTitlesSql)->execute();
$validTitlesIdx = 0;
while ($row = $getTitlesResult->fetchArray(SQLITE3_NUM)) {
    $validTitles[$validTitlesIdx] = $row[0];
    $validTitlesIdx++;
}

error_log(implode(", ", $validTitles));
$request = file_get_contents("php://input");

if (!$request) {
    echo '{"status": "400", "error": "Request body is empty!"}';
    echo http_response_code(400);
    exit;
}

$requestArray = json_decode($request, true);
$postTitle = removeUnsavoryCharacters(trim($requestArray["title"]));
if (!in_array($db->escapeString($postTitle), $validTitles, false)) {
    echo json_encode([
        "status" => "400",
        "error" => "${postTitle} does not match any of".implode(", ", $validTitles)
    ]);
    echo http_response_code(400);
    exit;
}

$statementGetPost = $db->prepare("SELECT * FROM post where title=:postTitle");
$statementGetPost->bindParam(":postTitle", $postTitle);
$rawGetPostSql = $statementGetPost->getSQL(true);
error_log("checking for ${postTitle} in database...");
error_log($rawGetPostSql);

$postResult = $statementGetPost->execute();

if (!$postResult) {
    echo json_encode([
        "error"=>"500",
        "message"=>"No post found with id ${postTitle}."
    ]);
    http_response_code(500);
    exit;
} 

$post = $postResult->fetchArray(SQLITE3_ASSOC);
$postJson = json_encode($post);
echo($postJson);
?>
