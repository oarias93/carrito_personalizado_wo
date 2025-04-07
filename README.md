***PLUGIN WORDPRESS**

// 1. Requisitos

- WordPress 5.0 o superior.
- WooCommerce 4.0 o superior.
- WooCommerce debe estar instalado y activo, y debe tener productos configurados para poder realizar pruebas.
- PHP 7.2 o superior.
- Este plugin debe ser instalado en un sitio de WordPress que ya tenga WooCommerce funcionando, ya que depende de su sistema de carrito.


// 2. Instalación


- Descargar el archivo del plugin desde el repositorio de GitHub. https://github.com/- Descarga el archivo del plugin desde el repositorio de GitHub: https://github.com/oarias93/carrito_personalizado_wo/.
- Descomprime el archivo descargado.
- Accede al directorio wp-content/plugins/ en la instalación de WordPress.
- Crea una carpeta con un nombre descriptivo, por ejemplo, carrito-personalizado dentro de wp-content/plugins/.
- Sube el contenido de la carpeta "wordpress" (del material descargado de GitHub) a la carpeta carrito-personalizado.
- Accede al panel de administración de WordPress.
- En el menú izquierdo de WordPress, ve a Plugins > Plugins Instalados y activa el plugin "Carrito personalizado para WooCommerce".


// 3. Configuración del plugin

- No se requiere configuración adicional más allá de la instalación y activación del plugin. Sin embargo, el plugin se utiliza a través de un shortcode [mostrar_carrito], el cual permite añadir el widget del carrito a cualquier página del sitio. Para hacerlo, sigue estos pasos:

	1. Crea una nueva página o edita una página existente en WordPress.
	2. En el contenido de la página, añade el siguiente shortcode: [mostrar_carrito].
	3 .Publica la página.
	4. El widget flotante del carrito se mostrará automáticamente en esa página y se actualizará dinámicamente cada vez que el carrito cambie.
	5. Se puede modificar el plugin para que el widget aparezca automáticamente en todas las páginas del sitio, si así fuera necesario.


//4. Funcionalidades Principales

Mostrar el carrito: El accionable del botón flotante muestra el carrito de compras de WooCommerce con los artículos añadidos, su cantidad, precio y subtotal.

Modificar cantidades: Los usuarios pueden aumentar o disminuir la cantidad de un producto directamente desde el widget.

Eliminar artículos: El usuario puede eliminar productos del carrito sin necesidad de recargar la página.

Interacción en tiempo real: El carrito se actualiza dinámicamente, sin recargar la página.

Responsivo: El widget se adapta al tamaño de la pantalla y es accesible en dispositivos móviles.
