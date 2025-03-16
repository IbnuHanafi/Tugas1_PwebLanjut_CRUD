<?php
require_once 'config.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Hapus produk dengan prepared statement
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
