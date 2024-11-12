<?php
include 'db.php';

$postId = $_GET['id'];
$sql = "SELECT announcement_title AS title, description_text AS description, announcement_images AS images FROM barangay_announcements WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$postId]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);
$post['images'] = json_decode($post['images']); // Decode images JSON to array

header('Content-Type: application/json');
echo json_encode($post);
?>
