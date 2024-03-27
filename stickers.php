<?php

//$sql = "CREATE TABLE stickers(cmd varchar, url varchar);";

//Create sqlite db if dne with schema above
function openStickerDB(){
    $db = new SQLite3("stickers.db");
    //$query = $db->query("SELECT * FROM stickers");
    //echo "numColumns count = ". $query->numColumns();
    //echo var_dump($query->fetchArray(SQLITE3_ASSOC));

    //echo var_dump($db->query("SELECT * FROM stickers;")->fetchArray());
    //$db->close();
    return $db;
}

//Insert markov commands 
function addCMD($db, $cmd, $url){
    $statement = $db->prepare('REPLACE INTO stickers (cmd, url) VALUES(:cmd, :url)');
    $statement->bindValue(':cmd', $cmd, SQLITE3_TEXT);
    $statement->bindValue(':url', $url, SQLITE3_TEXT);
    var_dump( $statement->execute());
}

//Echo saved command in discord 
function getCMD($db, $cmd){
    $statement = $db->prepare('SELECT url FROM stickers WHERE cmd=:cmd');
    $statement->bindValue(':cmd', $cmd, SQLITE3_TEXT);
    $result = $statement->execute();
    $result->numColumns();
    if($result->fetchArray(SQLITE3_ASSOC)){ 
        return $result->fetchArray(SQLITE3_ASSOC)["url"];
    } else{
        return false;
    }
}

//Import (command, url) pairs from old json
function migrateDB($db, $JSONpath){
    $json = file_get_contents($JSONpath);
    $json_data = json_decode($json, true);
    foreach($json_data as $pair){
        echo "ADDING: " . $pair['cmd'].PHP_EOL;
        addCMD($db, $pair['cmd'], $pair['url']);
    }
}

?>
