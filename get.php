<?php
require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    throw new Exception("Invalid request method {$_SERVER['REQUEST_METHOD']}.");
  }
  $data = [];
  $users = $pdo->query('SELECT * FROM users');
  foreach ($users as $row) {
    array_push($data, [
      'id'         => $row['id'],
      'first_name' => $row['first_name'],
      'last_name'  => $row['last_name'],
      'email'      => $row['email'],
      'created_at' => $row['created_at'],
    ]);
  }

  header("HTTP/1.1 200 OK");
  echo json_encode($data);
} catch (\PDOException $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail']);
} catch (Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}