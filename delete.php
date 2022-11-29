<?php
require_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$request = json_decode($json, true);

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    throw new Exception("Invalid request method {$_SERVER['REQUEST_METHOD']}.");
  }
  if (empty($request['id'])) {
    throw new Exception("Required id are missing.");
  }

  $id = $request['id'];
  $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
  $stmt->execute([$id]);
  $count = $stmt->rowCount();

  if (!$count) {
    throw new Exception("The id {$id} is not found.");
  }

  echo json_encode(['status' => 'success']);
} catch (\PDOException $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail']);
} catch (\Exception $e) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo json_encode(['status' => 'fail', 'message' => $e->getMessage()]);
}