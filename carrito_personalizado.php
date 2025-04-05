<?php   
/*
Plugin Name: Carrito personalizado para WooCommerce
Description: Un plugin para simular un carrito de compras en WooCommerce.
Author: Oscar Arias
*/

// Funcion para añadir estilos al carrito
function agregar_estilos_carrito_personalizado() {
    wp_enqueue_style('carrito-personalizado-css', plugin_dir_url(__FILE__) . '/scss/main.css');
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
    <!-- HTML Botón tipo sticky para mostrar el widget -->
    <div class="toggle-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" width="20" height="20" class="wc-block-mini-cart__icon" aria-hidden="true" focusable="false"><circle cx="12.6667" cy="24.6667" r="2" fill="currentColor"></circle><circle cx="23.3333" cy="24.6667" r="2" fill="currentColor"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M9.28491 10.0356C9.47481 9.80216 9.75971 9.66667 10.0606 9.66667H25.3333C25.6232 9.66667 25.8989 9.79247 26.0888 10.0115C26.2787 10.2305 26.3643 10.5211 26.3233 10.8081L24.99 20.1414C24.9196 20.6341 24.4977 21 24 21H12C11.5261 21 11.1173 20.6674 11.0209 20.2034L9.08153 10.8701C9.02031 10.5755 9.09501 10.269 9.28491 10.0356ZM11.2898 11.6667L12.8136 19H23.1327L24.1803 11.6667H11.2898Z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M5.66669 6.66667C5.66669 6.11438 6.1144 5.66667 6.66669 5.66667H9.33335C9.81664 5.66667 10.2308 6.01229 10.3172 6.48778L11.0445 10.4878C11.1433 11.0312 10.7829 11.5517 10.2395 11.6505C9.69614 11.7493 9.17555 11.3889 9.07676 10.8456L8.49878 7.66667H6.66669C6.1144 7.66667 5.66669 7.21895 5.66669 6.66667Z" fill="currentColor"></path></svg>
    </div>          
    <script type="text/javascript">
        jQuery(function($) {
            // Usar delegación de eventos para manejar clicks en elementos dinámicos
            
            // Aumentar cantidad
            $(document).on('click', '.cantidad-boton[data-action="increment"]', function() {
                var cart_item_key = $(this).data('item-key');
                var currentQuantity = parseInt($(this).siblings('.cantidad').text());
                var quantity = currentQuantity + 1;
                var wasVisible = $('.widgetCarrito').hasClass('show'); // Guardar estado de visibilidad

                // Actualizamos la cantidad de inmediato en la interfaz
                $(this).siblings('.cantidad').text(quantity);

                // Hacer la solicitud AJAX para actualizar en el backend
                actualizarCantidad(cart_item_key, quantity, wasVisible);
            });

            // Disminuir cantidad
            $(document).on('click', '.cantidad-boton[data-action="decrement"]', function() {
                var cart_item_key = $(this).data('item-key');
                var currentQuantity = parseInt($(this).siblings('.cantidad').text());
                var quantity = currentQuantity - 1;
                var wasVisible = $('.widgetCarrito').hasClass('show'); // Guardar estado de visibilidad

                // Evitar que la cantidad sea menor que 1
                if (quantity > 0) {
                    $(this).siblings('.cantidad').text(quantity);
                    actualizarCantidad(cart_item_key, quantity, wasVisible);
                }
            });

            // Eliminar artículo
            $(document).on('click', '.eliminar', function() {
                var cart_item_key = $(this).data('cart-item-key');
                var wasVisible = $('.widgetCarrito').hasClass('show'); // Guardar estado de visibilidad
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'eliminar_articulo',
                        cart_item_key: cart_item_key
                    },
                    success: function(response) {
                        // Actualizamos el carrito sin recargar la página
                        $('.widgetCarrito').replaceWith(response);
                        // Restaurar visibilidad si estaba visible
                        if (wasVisible) {
                            $('.widgetCarrito').addClass('show');
                        }
                    }
                });
            });

            // Función para actualizar la cantidad con AJAX
            function actualizarCantidad(cart_item_key, quantity, wasVisible) {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'actualizar_carrito',
                        cart_item_key: cart_item_key,
                        quantity: quantity
                    },
                    success: function(response) {
                        // Reemplazar todo el widget del carrito
                        $('.widgetCarrito').replaceWith(response);
                        // Restaurar visibilidad si estaba visible
                        if (wasVisible) {
                            $('.widgetCarrito').addClass('show');
                        }
                    },
                    error: function() {
                        alert("Hubo un error al actualizar el carrito.");
                    }
                });
            }

            // Mostrar/Ocultar el carrito cuando se haga clic en el botón flotante
            $(document).on('click', '.toggle-button', function() {
                $('.widgetCarrito').toggleClass('show');
                // Cambiar el texto del botón según el estado
                $(this).text($('.widgetCarrito').hasClass('show') ? '-' : '+');
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

// Hook para agregar el carrito al frontend
add_shortcode('mostrar_carrito', 'mostrar_carrito');

// Función auxiliar para generar el HTML del carrito (evita duplicación de código)
function generar_html_carrito() {
    ob_start();
    ?>
    <div class="widgetCarrito">
        <div class="toggle-button">+</div>
        <h2>Carrito PERSONALIZADO</h2>
        <ul class="widgetCarrito__list">
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
    return ob_get_clean();
}

// Función para actualizar el carrito con AJAX
function actualizar_carrito() {
    if (isset($_POST['action']) && $_POST['action'] == 'actualizar_carrito') {
        $cart_item_key = $_POST['cart_item_key'];
        $quantity = $_POST['quantity'];

        // Actualizar la cantidad del artículo en el carrito
        WC()->cart->set_quantity($cart_item_key, $quantity);

        // Devolver el HTML actualizado del carrito
        echo generar_html_carrito();
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

        // Devolver el HTML actualizado del carrito
        echo generar_html_carrito();
    }
    die();
}
add_action('wp_ajax_eliminar_articulo', 'eliminar_articulo');
add_action('wp_ajax_nopriv_eliminar_articulo', 'eliminar_articulo');
?>