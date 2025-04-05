<?php   
/*
Plugin Name: Carrito personalizado para woocomerce
Description: Un plugin para simular un carrito de compras en WooCommerce.
Author: Oscar Arias
*/


// Función que muestra el carrito en el frontend
function mostrar_carrito() {


    $cart = WC()->cart->get_cart();
    $subtotal = WC()->cart->get_cart_total();
    
    ob_start(); ?>
    
    <div class="widget-carrito">
        <h2>Carrito Personalizado</h2>
        <ul>
            <?php foreach ($cart as $item) : ?>
                <li>
                    <?php echo $item['data']->get_name(); ?> - 
                    <?php echo wc_price($item['line_total']); ?>
                    <form method="POST" action="">
                        <input type="number" name="cantidad" value="<?php echo $item['quantity']; ?>" />
                        <button type="submit">Actualizar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Subtotal: <?php echo $subtotal; ?></p>
    </div>
    
    <?php
    return ob_get_clean();
}

// Hook para agregar el carrito al frontend
add_shortcode('mostrar_carrito', 'mostrar_carrito');
?>

