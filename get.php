<?php
require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

$data = [];
$users = $pdo->query('SELECT * FROM users');
foreach ($users as $row) {
  array_push($data, [
    'id'         => $row['id'],
    'first_name' => $row['first_name'],
    'last_name'  => $row['last_name'],
    'email'      => $row['email'],
  ]);
}

echo json_encode($data);