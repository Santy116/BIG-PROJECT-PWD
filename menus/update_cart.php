<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['action'], $data['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    $id = $data['id'];

    if ($data['action'] === 'add') {
        $product = [
            'id' => $id,
            'name' => $data['name'],
            'price' => $data['price'],
            'image' => $data['image'],
            'quantity' => 1
        ];
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = $product;
        } else {
            $_SESSION['cart'][$id]['quantity']++;
        }
        echo json_encode(['success' => true]);
    } elseif ($data['action'] === 'update') {
        $qty = max(1, (int)$data['quantity']);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
        echo json_encode(['success' => true]);
    } elseif ($data['action'] === 'remove') {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
    }
}