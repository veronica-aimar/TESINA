<?php

class Connection
{
    public static function connect()
    {
        $conn = new PDO("sqlite:Farmaci.db");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}