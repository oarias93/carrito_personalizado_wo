document.addEventListener('DOMContentLoaded', function() {
  // Toggle del carrito
  document.addEventListener('click', function(e) {
      if (e.target.closest('.toggle-button')) {
          document.getElementById('widgetCarrito').classList.toggle('show');
      }
  });

  // Delegación de eventos para el carrito
  document.addEventListener('click', async function(e) {
      const target = e.target;
      const cartItem = target.closest('.widgetCarrito__item');
      
      if (!cartItem) return;
      
      const itemKey = cartItem.dataset.key;
      const quantityElement = cartItem.querySelector('.cantidad');
      let currentQty = parseInt(quantityElement.textContent);
      let newQty = currentQty;

      // Manejar aumento/disminución de cantidad
      if (target.classList.contains('cantidad-boton')) {
          const action = target.dataset.action;
          newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
          
          // Actualización inmediata en la UI
          quantityElement.textContent = newQty;
          await updateCartItem(itemKey, newQty, cartItem);
          return;
      }
      
      // Manejar eliminación de item
      if (target.classList.contains('eliminar')) {
          await updateCartItem(itemKey, 0, cartItem);
          cartItem.remove();
      }
      
      // Actualizar subtotal después de cualquier cambio
      updateCartSubtotal();
  });

  // Función para formatear moneda (reemplazo de Shopify.formatMoney)
  function formatMoney(cents) {
      return '$' + (cents / 100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  }

  // Función para actualizar el carrito
  async function updateCartItem(key, quantity, itemElement) {
      try {
          // Actualizar en Shopify
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
          
          // Actualizar precios si no es eliminación
          if (quantity > 0 && itemElement) {
              const item = cart.items.find(i => i.key === key);
              if (item) {
                  itemElement.querySelector('.widgetCarrito__price').textContent = formatMoney(item.line_price);
              }
          }
          
          return cart;
      } catch (error) {
          console.error('Error al actualizar carrito:', error);
          throw error;
      }
  }

  // Función para actualizar el subtotal
  async function updateCartSubtotal() {
      try {
          const response = await fetch('/cart.js');
          const cart = await response.json();
          document.querySelector('.widgetCarrito__subtotal').textContent = `Subtotal: ${formatMoney(cart.total_price)}`;
      } catch (error) {
          console.error('Error al actualizar subtotal:', error);
      }
  }
});