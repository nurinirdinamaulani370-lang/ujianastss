<?php

header("Content-Type: application/json; charset=UTF-8");

include "koneksi.php";

$query = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY id ASC"
);

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data, JSON_PRETTY_PRINT);

?>