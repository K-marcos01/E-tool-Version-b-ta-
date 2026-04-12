<?php
/**
 * add-to-cart.php — Action d'ajout au panier
 * ═══════════════════════════════════════════════════════════════
 * Reçoit : POST product_id, quantity
 * Renvoie : JSON
 *   { success: true,  cart_count: N }                 ← connecté
 *   { success: false, redirect: "URL_LOGIN" }          ← non connecté
 *   { success: false, message: "..." }                 ← erreur
 *
 * Le JS de Shop-detail.js lit la réponse :
 *   - Si redirect → window.location = redirect
 *   - Si success  → met à jour le badge panier + toast
 *   - Si message  → affiche le message d'erreur
 * ═══════════════════════════════════════════════════════════════
 */
session_start();

/* Sécurité : POST uniquement, réponse JSON */
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit();
}

/* ── Vérifie si l'utilisateur est connecté ──────────────────── */
if (!isset($_SESSION['user_id'])) {
    /*
     * Non connecté → on renvoie une URL de redirection vers Register.
     * Le paramètre redirect_to permet de revenir sur la boutique
     * après inscription/connexion.
     * On reconstruit l'URL de la boutique depuis le Referer.
     */
    $shopUrl    = $_SERVER['HTTP_REFERER'] ?? '../Int_Clients/Home.php';
    $registerUrl = '../Auth/Register.php?redirect_to=' . urlencode($shopUrl);

    echo json_encode([
        'success'  => false,
        'redirect' => $registerUrl,
        'message'  => 'Connexion requise pour commander.',
    ]);
    exit();
}

/* ── Valide les paramètres ───────────────────────────────────── */
$productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]);
$quantity  = filter_input(INPUT_POST, 'quantity',   FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1, 'max_range' => 99]]) ?? 1;

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'Produit invalide.']);
    exit();
}

/* ── Charge les données produit ──────────────────────────────── */
require_once '../Int_Clients/Shops-data.php';

$allProducts = $PRODUCTS_DATA;
$product     = null;

foreach ($allProducts as $p) {
    if ((int)$p['id'] === $productId) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
    exit();
}

/* ── Vérifie le stock ────────────────────────────────────────── */
if ((int)$product['stock'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Ce produit est en rupture de stock.']);
    exit();
}

/* ── Ajoute au panier en session ─────────────────────────────── */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ((int)$item['id'] === $productId) {
        $item['qty'] = min($item['qty'] + $quantity, (int)$product['stock']);
        $found = true;
        break;
    }
}
unset($item);

if (!$found) {
    $_SESSION['cart'][] = [
        'id'    => $productId,
        'name'  => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty'   => $quantity,
    ];
}

/* ── Renvoie le succès avec le nombre d'articles ─────────────── */
$cartCount = array_sum(array_column($_SESSION['cart'], 'qty'));

echo json_encode([
    'success'    => true,
    'cart_count' => $cartCount,
    'message'    => htmlspecialchars($product['name']) . ' ajouté au panier.',
]);
exit();
