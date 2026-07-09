<?php

require_once __DIR__ . '/../config/database.php';

class Payment
{
    public static function all($conn)
    {
        return mysqli_query($conn, "SELECT * FROM payments ORDER BY id DESC");
    }

    public static function find($conn, $id)
    {
        $id = (int)$id;

        return mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM payments WHERE id=$id")
        );
    }
}

?>