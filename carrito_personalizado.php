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

        // Obtener los artículos en el carrito y el subtotal
        $cart = WC()->cart->get_cart();
        $subtotal = WC()->cart->get_cart_total();
        
        ob_start(); ?>
        
        <!-- HTML para el widget del carrito -->
        <div class="widgetCarrito">
            <h2>Carrito PERSONALIZADO</h2>
            <ul class="widgetCarrito__list">
                <?php foreach ($cart as $item) : ?>
                    <li data-cart-item-key="<?php echo $item['key']; ?>"  class="widgetCarrito__item">
                        <?php echo $item['data']->get_name(); ?> - 
                        <?php echo wc_price($item['line_total']); ?>
                        <!-- Formulario para actualizar cantidad -->
                        <form method="POST" class="form-actualizar">
                            <input type="number" name="cantidad" value="<?php echo $item['quantity']; ?>" />
                            <button type="submit">Actualizar</button>
                        </form>
                        <!-- Botón para eliminar artículo -->
                        <button class="eliminar" onclick="eliminarArticulo('<?php echo $item['key']; ?>')">Eliminar</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Subtotal: <?php echo $subtotal; ?></p>
        </div>

        <!-- HTML Botón tipo sticky para mostrar el widget -->
        <div class="toggle-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" width="20" height="20" class="wc-block-mini-cart__icon" aria-hidden="true" focusable="false"><circle cx="12.6667" cy="24.6667" r="2" fill="currentColor"></circle><circle cx="23.3333" cy="24.6667" r="2" fill="currentColor"></circle><path fill-rule="evenodd" clip-rule="evenodd" d="M9.28491 10.0356C9.47481 9.80216 9.75971 9.66667 10.0606 9.66667H25.3333C25.6232 9.66667 25.8989 9.79247 26.0888 10.0115C26.2787 10.2305 26.3643 10.5211 26.3233 10.8081L24.99 20.1414C24.9196 20.6341 24.4977 21 24 21H12C11.5261 21 11.1173 20.6674 11.0209 20.2034L9.08153 10.8701C9.02031 10.5755 9.09501 10.269 9.28491 10.0356ZM11.2898 11.6667L12.8136 19H23.1327L24.1803 11.6667H11.2898Z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M5.66669 6.66667C5.66669 6.11438 6.1144 5.66667 6.66669 5.66667H9.33335C9.81664 5.66667 10.2308 6.01229 10.3172 6.48778L11.0445 10.4878C11.1433 11.0312 10.7829 11.5517 10.2395 11.6505C9.69614 11.7493 9.17555 11.3889 9.07676 10.8456L8.49878 7.66667H6.66669C6.1144 7.66667 5.66669 7.21895 5.66669 6.66667Z" fill="currentColor"></path></svg>
        </div>

        <!-- Scrit para controlar el comportamiento del carrito-->
        <script type="text/javascript">
            jQuery(function($) {

                // Funcion para el boton del sticky
                $('.toggle-button').on('click', function() {
                    $('.widgetCarrito').toggleClass('show');
                });

                // Actualizar cantidad del carrito con AJAX
                $('button[type="submit"]').on('click', function(e) {
                    e.preventDefault();
                    var cart_item_key = $(this).closest('li').data('cart-item-key');
                    var quantity = $(this).prev('input[name="cantidad"]').val();

                    $.ajax({
                        url: '/wp-admin/admin-ajax.php',
                        method: 'POST',
                        data: {
                            action: 'actualizar_carrito',
                            cart_item_key: cart_item_key,
                            quantity: quantity
                        },
                        success: function(response) {
                            $('.widget-carrito').html(response); // Actualizar el contenido del carrito
                        }
                    });
                });

            });

            // Función para eliminar artículo con AJAX
            function eliminarArticulo(cart_item_key) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    method: 'POST',
                    data: {
                        action: 'eliminar_articulo',
                        cart_item_key: cart_item_key
                    },
                    success: function(response) {
                        $('.widget-carrito').html(response); // Actualizar el carrito
                    }
                });
            }

        </script>

        <?php
        return ob_get_clean();
    }

    // Hook para agregar el carrito al frontend
    add_shortcode('mostrar_carrito', 'mostrar_carrito');
?>

