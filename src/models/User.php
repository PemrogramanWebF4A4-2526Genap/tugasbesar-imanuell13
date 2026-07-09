<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    public static function all($conn)
    {
        return mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
    }

    public static function find($conn, $id)
    {
        $id = (int)$id;

        return mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM users WHERE id=$id")
        );
    }
}

?>