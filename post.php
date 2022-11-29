<?php
require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$request = json_decode($json, true);

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception("Invalid request method {$_SERVER['REQUEST_METHOD']}.");
  }
  if (empty($request['first_name']) || empty($request['last_name']) || empty($request['email'])) {
    throw new Exception("Required inputs are missing.");
  }

  $first_name = $request['first_name'];
  $last_name = $request['last_name'];
  $email = $request['email'];

  $data = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
  ];
  
  $sql = "INSERT INTO users (first_name, last_name, email) VALUES (:first_name, :last_name, :email)";
  $stmt= $pdo->prepare($sql);
  $stmt->execute($data);
  $id = $pdo->lastInsertId();
  $data['id'] = $id;
  
  echo json_encode(['status' => 'success', 'data' => $data]);
} catch (\PDOException $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail']);
} catch (\Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}