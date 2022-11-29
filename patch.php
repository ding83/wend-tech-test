<?php
require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$request = json_decode($json, true);

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    throw new Exception("Invalid request method {$_SERVER['REQUEST_METHOD']}.");
  }
  if (
    empty($request['id'])
    || empty($request['first_name'])
    || empty($request['last_name'])
    || empty($request['email'])
  ) {
    throw new Exception("Required inputs are missing.");
  }

  $id = $request['id'];
  $first_name = $request['first_name'];
  $last_name = $request['last_name'];
  $email = $request['email'];

  $data = [
    'id' => $id,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
  ];
  
  $sql = "UPDATE users SET first_name=:first_name, last_name=:last_name, email=:email WHERE id=:id";
  $stmt= $pdo->prepare($sql);
  $stmt->execute($data);
  $count = $stmt->rowCount();

  if (!$count) {
    throw new Exception("The id {$id} is not found.");
  }

  header("HTTP/1.1 200 OK");
  echo json_encode(['status' => 'success', 'data' => $data]);
} catch (\PDOException $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail']);
} catch (\Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}
