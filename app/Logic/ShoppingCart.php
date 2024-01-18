<?php
require_once __DIR__ . '/../Models/Ad.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function addItemToShoppingCart($item): void
{
    if (!isset($_SESSION['cartItems']) || !is_array($_SESSION['cartItems'])) {
        $_SESSION['cartItems'] = [];
    }

    $_SESSION['cartItems'][] = $item;
    updateCountOfItemsInCart();
}
function removeItemFromCart($item): void
{
    $cartItems = unserialize(serialize($_SESSION['cartItems']));
    $_SESSION['cartItems'] = array_filter($cartItems, function ($shoppingCartItem) use ($item) {
        return !$item->__equals($shoppingCartItem);
    });
    updateCountOfItemsInCart();
}
function updateItemCountInSession(): void
{
    updateCountOfItemsInCart();
    $_SESSION['itemCount'] = $_SESSION['countShoppingCartItems'];
}
function updateCountOfItemsInCart(): void
{
    if (isset($_SESSION['cartItems']) && is_array($_SESSION['cartItems'])) {
        $_SESSION['countShoppingCartItems'] = count($_SESSION['cartItems']);
    } else {
        // Handle the case where cartItems is not set or not an array
        $_SESSION['countShoppingCartItems'] = 0;
    }
}

function getTotalAmountOfItemsInShoppingCart() {
    $totalAmount = 0;

    if (isset($_SESSION['cartItems']) && is_array($_SESSION['cartItems'])) {
        foreach ($_SESSION['cartItems'] as $ad) {
            $totalAmount += $ad->getProductPrice();
        }
    }

    return $totalAmount;
}


function checkTheExistenceOfItemInCart($item): bool
{
    $cartItems = unserialize(serialize($_SESSION['cartItems']));
    return in_array($item, $cartItems);
}

function getItemsInShoppingCart()
{
    return unserialize(serialize($_SESSION['cartItems']));
}

function clearShoppingCart(): void
{
    unset($_SESSION['cartItems']);
    updateCountOfItemsInCart(); // Update the count after clearing the cart
}
