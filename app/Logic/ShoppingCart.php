<?php
require_once __DIR__ . '/../Models/Ad.php';
session_start();

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
    updateCountOfItemsInCart(); // Assuming this function updates the count
    $_SESSION['itemCount'] = $_SESSION['countShoppingCartItems'];
}
function updateCountOfItemsInCart(): void
{
    $_SESSION['countShoppingCartItems'] = count($_SESSION['cartItems']);
}

function getTotalAmountOfItemsInShoppingCart(): float
{
    $total = 0.0;

    if (isset($_SESSION['cartItems']) && is_array($_SESSION['cartItems'])) {
        foreach ($_SESSION['cartItems'] as $cartItem) {
            // Ensure that $cartItem is an instance of Ad with a getPrice method
            if ($cartItem instanceof Ad && method_exists($cartItem, 'getPrice')) {
                $total += $cartItem->getPrice();
            }
        }
    }

    return $total;
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
