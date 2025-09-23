<?php

function createDb($dbName){
    if (file_exists($dbName)){
        echo "Database already exists! Aborting...\n";
        exit;
    } else {
        $database = new SQLite3($dbName);
        echo "Created database $dbName\n";
        return $database;
    }
}

function getDbFile(SQLite3 $db){
    $fileName = "";
    while ($row = $db->query("pragma database_info")->fetchArray()){
        $fileName = $row["file"];
    }
    return $fileName;
}

function createTable($db, $tableName){
    $dbName = getDbFile($db);
    $sql = "CREATE  TABLE IF NOT EXISTS $tableName (id INTEGER PRIMARY KEY,
                                                    title TEXT NOT NULL,
                                                    author TEXT NOT NULL,
                                                    date TEXT NOT NULL,
                                                    content TEXT NOT NUll)";
    $db->exec($sql);
    echo "Created table $tableName in database $dbName\n";
}

$db = createDb($db="../data/posts.db");
createTable($db, $tableName="post");
?>
