<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\Request;

trait CartTrait {
    public function cartQuantity(Request $request): int
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);
        $cartQuantity = 0;
        foreach ($cart as $quantity) {
            $cartQuantity += $quantity;
        }

        return $cartQuantity;
    }
}