<?php

class chatDB_info
{
    const DB_url = "localhost";
    const DB_host = "root";
    const DB_PW = "autoset";
    const DB_name = "chat";
}

$DB_info = new chatDB_info();

$chat_Query = new mysqli($DB_info::DB_url, $DB_info::DB_host, $DB_info::DB_PW, $DB_info::DB_name);


class process
{
    public function delete($table, $record, $value)
    {
        global $chat_Query;
        $chat_Query->query("delete from $table where $record='$value'");
    }

    public function update($table, $record1, $value1, $record2, $value2)
    {
        global $chat_Query;
        $chat_Query->query("update $table set $record1='$value1' where $record2='$value2'");
    }

    public function insert($table, $insert_sentence)
    {
        global $chat_Query;
        $chat_Query->query("insert into $table values $insert_sentence");
    }

    public function select($record, $table, $select_sentence)
    {
        global $chat_Query;
        $chat_Query->query("select $record from $table $select_sentence");
    }
}

?>