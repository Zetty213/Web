<?php
require 'config.php';

$body = $_POST['body'];
$imagePath = null;

if (!empty($_FILES['image']['name'])) {
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        die('画像は5MB以下にしてください');
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $filename);
    $imagePath = 'uploads/' . $filename;
}

$stmt = $pdo->prepare('INSERT INTO posts (body, image_path) VALUES (?, ?)');
$stmt->execute([$body, $imagePath]);

header('Location: index.php');
exit;