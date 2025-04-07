***PLUGIN WORDPRESS**

// 1. Requisitos

- WordPress 5.0 o superior.
- WooCommerce 4.0 o superior.
- WooCommerce debe estar instalado y activo, y debe tener productos configurados para poder realizar pruebas.
- PHP 7.2 o superior.
- Este plugin debe ser instalado en un sitio de WordPress que ya tenga WooCommerce funcionando, ya que depende de su sistema de carrito.


// 2. Instalación


- Descarga el archivo del plugin desde el repositorio de GitHub: https://github.com/oarias93/carrito_personalizado_wo/.
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




***WIDGET SHOPIFY**

// 1. Requisitos

- Una tienda activa en Shopify.
- Para desarrollo y pruebas locales, tener instalado Node.js y Shopify CLI .
- El plugin debe ser instalado en un tema de Shopify en el que tengas acceso para personalizar y editar archivos Liquid.


// 2. Instalación Ambiente Local-Pruebas


- Descarga el archivo del plugin desde el repositorio de GitHub: https://github.com/oarias93/carrito_personalizado_wo/.
- Descomprime el archivo descargado.
- Inicia sesión en tu panel de administración de Shopify.
- Ve a Tiendas Online" > Temas para acceder a los archivos de tu tema actual.
- Localiza el tema que esta activo y da click en el botón de editar codigo
- En la sección Snippets/, haz clic en "Agregar nuevo fragmento".
- Nombra el nuevo archivo como carrito-personalizado.liquid
- En este archivo, agrega el código del archivo "carrito-personalizado.liquid" que has descargado de GitHub ubicado la carpeta shopify/snnipets, finalmente da click en guardar
- Ahora localiza a la carpeta assets en la administracion de shopify, da click en  "Agregar nuevo recurso".
- Ahora busca en tu equipo el archivo carrito.js que has descargado de GitHub ubicado la carpeta shopify/assets, finalmente da click en guardar
- Finalmente bre el archivo theme.liquid que se encuentra en la carpeta layout. Coloca el siguiente código  antes de la etiqueta </body>:

{% render 'carrito-personalizado' %}
{{ 'carrito.js' | asset_url | script_tag }}


// 3. Configuración del Widget


El widget se mostrará en el sitio automáticamente sin necesidad de configuraciones adicionales. Sin embargo, si deseas personalizar su comportamiento o su apariencia, puedes hacerlo editando los archivos Liquid y JavaScript.

Comportamiento: Puedes ajustar el comportamiento del widget en el archivo carrito.js, como los botones de incrementar y decrementar la cantidad, el botón de eliminar productos, y cómo se actualiza el subtotal.


//4. Funcionalidades Principales

Mostrar el carrito: El accionable del botón flotante muestra el carrito de compras de shopify con los artículos añadidos, su cantidad, precio y subtotal.

Modificar cantidades: Los usuarios pueden aumentar o disminuir la cantidad de un producto directamente desde el widget.

Eliminar artículos: El usuario puede eliminar productos del carrito sin necesidad de recargar la página.

Interacción en tiempo real: El carrito se actualiza dinámicamente, sin recargar la página.

Responsivo: El widget se adapta al tamaño de la pantalla y es accesible en dispositivos móviles.
