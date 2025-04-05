<?php   
/*
Plugin Name: Carrito personalizado para WooCommerce
Description: Un plugin para simular un carrito de compras en WooCommerce.
Author: Oscar Arias
*/

// Funcion para añadir estilos al carrito
function agregar_estilos_carrito_personalizado() {
    wp_enqueue_style('carrito-personalizado-css', plugin_dir_url(__FILE__) . '/style.css');
}
add_action('wp_enqueue_scripts', 'agregar_estilos_carrito_personalizado');

// Función que muestra el carrito en el frontend
function mostrar_carrito() {
    // Obtener los artículos en el carrito y el subtotal
    $cart = WC()->cart->get_cart();
    $subtotal = WC()->cart->get_cart_total();
    
    ob_start(); ?>

    <!-- HTML para el widget del carrito -->
    <div class="widgetCarrito">
        <div class="toggle-button">+</div> <!-- Botón flotante dentro del widget -->

        <h2>Carrito PERSONALIZADO</h2>
        <ul class="widgetCarrito__list">
            <?php foreach ($cart as $item) : ?>
                <li data-cart-item-key="<?php echo $item['key']; ?>">
                    <?php echo $item['data']->get_name(); ?> - 
                    <?php echo wc_price($item['line_total']); ?>
                    
                    <!-- Botones para aumentar y disminuir cantidades -->
                    <button class="cantidad-boton" data-action="decrement" data-item-key="<?php echo $item['key']; ?>">-</button>
                    <span class="cantidad"><?php echo $item['quantity']; ?></span>
                    <button class="cantidad-boton" data-action="increment" data-item-key="<?php echo $item['key']; ?>">+</button>
                    
                    <!-- Botón para eliminar artículo -->
                    <button class="eliminar" data-cart-item-key="<?php echo $item['key']; ?>">Eliminar</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Subtotal: <span class="subtotal"><?php echo $subtotal; ?></span></p>
    </div>

    <script type="text/javascript">
        jQuery(function($) {
            // Aumentar cantidad
            $('.cantidad-boton[data-action="increment"]').on('click', function() {
                var cart_item_key = $(this).data('item-key');
                var currentQuantity = parseInt($(this).siblings('.cantidad').text());
                var quantity = currentQuantity + 1;

                // Actualizamos la cantidad de inmediato en la interfaz
                $(this).siblings('.cantidad').text(quantity);

                // Hacer la solicitud AJAX para actualizar en el backend
                actualizarCantidad(cart_item_key, quantity);
            });

            // Disminuir cantidad
            $('.cantidad-boton[data-action="decrement"]').on('click', function() {
                var cart_item_key = $(this).data('item-key');
                var currentQuantity = parseInt($(this).siblings('.cantidad').text());
                var quantity = currentQuantity - 1;

                // Evitar que la cantidad sea menor que 1
                if (quantity > 0) {
                    $(this).siblings('.cantidad').text(quantity);
                    actualizarCantidad(cart_item_key, quantity);
                }
            });

            // Eliminar artículo
            $('.eliminar').on('click', function() {
                var cart_item_key = $(this).data('cart-item-key');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'eliminar_articulo',
                        cart_item_key: cart_item_key
                    },
                    success: function(response) {
                        // Actualizamos el carrito sin recargar la página
                        $('.widgetCarrito').html(response); 
                    }
                });
            });

            // Función para actualizar la cantidad con AJAX
            function actualizarCantidad(cart_item_key, quantity) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',  // Usamos la URL correcta de admin-ajax.php
                    method: 'POST',
                    data: {
                        action: 'actualizar_carrito',
                        cart_item_key: cart_item_key,
                        quantity: quantity
                    },
                    success: function(response) {
                        // Actualizamos el carrito y mantenemos el widget abierto
                        $('.widgetCarrito').html(response); 
                    },
                    error: function() {
                        alert("Hubo un error al actualizar el carrito.");
                    }
                });
            }

            // Mostrar/Ocultar el carrito cuando se haga clic en el botón flotante
            $('.toggle-button').on('click', function() {
                $('.widgetCarrito').toggleClass('show');
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

// Hook para agregar el carrito al frontend
add_shortcode('mostrar_carrito', 'mostrar_carrito');

// Función para actualizar el carrito con AJAX
function actualizar_carrito() {
    if (isset($_POST['action']) && $_POST['action'] == 'actualizar_carrito') {
        $cart_item_key = $_POST['cart_item_key'];
        $quantity = $_POST['quantity'];

        // Actualizar la cantidad del artículo en el carrito
        WC()->cart->set_quantity($cart_item_key, $quantity);

        // Recargar el carrito
        ob_start();
        ?>
        <div class="widgetCarrito">
            <ul>
                <?php
                $cart = WC()->cart->get_cart();
                foreach ($cart as $item_key => $item) : ?>
                    <li data-cart-item-key="<?php echo $item_key; ?>">
                        <?php echo $item['data']->get_name(); ?> - 
                        <?php echo wc_price($item['line_total']); ?>
                        
                        <button class="cantidad-boton" data-action="decrement" data-item-key="<?php echo $item_key; ?>">-</button>
                        <span class="cantidad"><?php echo $item['quantity']; ?></span>
                        <button class="cantidad-boton" data-action="increment" data-item-key="<?php echo $item_key; ?>">+</button>
                        
                        <button class="eliminar" data-cart-item-key="<?php echo $item_key; ?>">Eliminar</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Subtotal: <span class="subtotal"><?php echo WC()->cart->get_cart_total(); ?></span></p>
        </div>
        <?php
        echo ob_get_clean();
    }
    die();
}
add_action('wp_ajax_actualizar_carrito', 'actualizar_carrito');
add_action('wp_ajax_nopriv_actualizar_carrito', 'actualizar_carrito');

// Función para eliminar un artículo del carrito con AJAX
function eliminar_articulo() {
    if (isset($_POST['action']) && $_POST['action'] == 'eliminar_articulo') {
        $cart_item_key = $_POST['cart_item_key'];
        
        // Eliminar el artículo del carrito
        WC()->cart->remove_cart_item($cart_item_key);

        // Recargar el carrito
        ob_start();
        ?>
        <div class="widgetCarrito">
            <ul>
                <?php
                $cart = WC()->cart->get_cart();
                foreach ($cart as $item_key => $item) : ?>
                    <li data-cart-item-key="<?php echo $item_key; ?>">
                        <?php echo $item['data']->get_name(); ?> - 
                        <?php echo wc_price($item['line_total']); ?>
                        <button class="cantidad-boton" data-action="decrement" data-item-key="<?php echo $item_key; ?>">-</button>
                        <span class="cantidad"><?php echo $item['quantity']; ?></span>
                        <button class="cantidad-boton" data-action="increment" data-item-key="<?php echo $item_key; ?>">+</button>
                        <button class="eliminar" data-cart-item-key="<?php echo $item_key; ?>">Eliminar</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Subtotal: <span class="subtotal"><?php echo WC()->cart->get_cart_total(); ?></span></p>
        </div>
        <?php
        echo ob_get_clean();
    }
    die();
}
add_action('wp_ajax_eliminar_articulo', 'eliminar_articulo');
add_action('wp_ajax_nopriv_eliminar_articulo', 'eliminar_articulo');
?>
