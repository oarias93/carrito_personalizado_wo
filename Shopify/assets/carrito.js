document.addEventListener('DOMContentLoaded', function() {
  // Mostrar y ocultar el carrito
  document.querySelector('.toggle-button').addEventListener('click', function() {
      document.getElementById('widgetCarrito').classList.toggle('show');
  });

  // Delegación de eventos para los botones de aumentar y disminuir cantidades
  document.getElementById('widgetCarrito').addEventListener('click', function(e) {
      const target = e.target;
      const cartItem = target.closest('.widgetCarrito__item');
      
      if (!cartItem) return;

      const itemKey = cartItem.dataset.key;
      const currentQty = parseInt(cartItem.querySelector('.cantidad').textContent);
      let newQty = currentQty;

      // Aumentar o disminuir cantidad
      if (target.classList.contains('cantidad-boton')) {
          const action = target.dataset.action;
          newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
          updateCartItem(itemKey, newQty);
      }

      // Eliminar el artículo
      if (target.classList.contains('eliminar')) {
          updateCartItem(itemKey, 0);
      }
  });

  // Función para actualizar el carrito con AJAX
  async function updateCartItem(key, quantity) {
      const wasVisible = document.getElementById('widgetCarrito').classList.contains('show');
      
      try {
          // Actualizamos la cantidad en el carrito mediante AJAX
          const response = await fetch('/cart/change.js', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json'
              },
              body: JSON.stringify({
                  id: key,
                  quantity: quantity
              })
          });
          
          const cart = await response.json();
          if (cart.errors) {
              alert("Hubo un error al actualizar el carrito");
              return;
          }

          // Refrescamos el carrito con los nuevos datos
          refreshCart(cart);

          // Si el widget estaba visible, mantenemos el estado visible
          if (wasVisible) {
              document.getElementById('widgetCarrito').classList.add('show');
          }
      } catch (error) {
          console.error('Error al actualizar el carrito:', error);
      }
  }

  // Función para refrescar el carrito en el frontend
  async function refreshCart(cart) {
      // Actualizamos los elementos del carrito en el frontend
      const cartList = document.querySelector('.widgetCarrito__list');
      cartList.innerHTML = ''; // Limpiar los items

      cart.items.forEach(item => {
          const li = document.createElement('li');
          li.classList.add('widgetCarrito__item');
          li.dataset.key = item.key;
          
          // Crear el contenido HTML del carrito con los nuevos datos
          li.innerHTML = `
              <h3>${item.product.title}</h3>
              <span class="widgetCarrito__price">${item.line_price | money}</span>
              <div class="widgetCarrito__cantidad">
                  <button class="cantidad-boton" data-action="decrease">-</button>
                  <span class="cantidad">${item.quantity}</span>
                  <button class="cantidad-boton" data-action="increase">+</button>
              </div>
              <button class="eliminar">Eliminar</button>
          `;
          cartList.appendChild(li);
      });

      // Actualizar el subtotal
      const subtotalElement = document.querySelector('.widgetCarrito__subtotal');
      subtotalElement.textContent = `Subtotal: ${cart.total_price | money}`;
  }
});
