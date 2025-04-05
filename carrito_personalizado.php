<?php   
/*
Plugin Name: Carrito personalizado para woocomerce
Description: Un plugin para simular un carrito de compras en WooCommerce.
Author: Oscar Arias
*/


// Funcion para añdir estilos al carrito
function agregar_estilos_carrito_personalizado() {
    wp_enqueue_style('carrito-personalizado-css', plugin_dir_url(__FILE__) . '/scss/main.css');
}
add_action('wp_enqueue_scripts', 'agregar_estilos_carrito_personalizado');


// Función que muestra el carrito en el frontend
function mostrar_carrito() {

    $cart = WC()->cart->get_cart();
    $subtotal = WC()->cart->get_cart_total();
    
    ob_start(); ?>
    
    <div class="widgetCarrito">
        <h2>Carrito Personalizado</h2>
        <ul class="widgetCarrito__list">
            <?php foreach ($cart as $item) : ?>
                <li class="widgetCarrito__item">
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

