document.addEventListener('DOMContentLoaded', function() {
    // Toggle del carrito
    document.querySelector('.toggle-button').addEventListener('click', function() {
      document.getElementById('widgetCarrito').classList.toggle('show');
    });
  
    // Delegación de eventos para los botones
    document.getElementById('widgetCarrito').addEventListener('click', function(e) {
      const target = e.target;
      const cartItem = target.closest('.widgetCarrito__item');
      
      if (!cartItem) return;
      
      const itemKey = cartItem.dataset.key;
      const currentQty = parseInt(cartItem.querySelector('.cantidad').textContent);
      let newQty = currentQty;
  
      // Manejar aumento/disminución de cantidad
      if (target.classList.contains('cantidad-boton')) {
        const action = target.dataset.action;
        newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
        updateCartItem(itemKey, newQty);
      }
      
      // Manejar eliminación de item
      if (target.classList.contains('eliminar')) {
        updateCartItem(itemKey, 0);
      }
    });
  
    // Función para actualizar el carrito
    async function updateCartItem(key, quantity) {
      const wasVisible = document.getElementById('widgetCarrito').classList.contains('show');
      
      try {
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
        refreshCart(cart);
        
        if (wasVisible) {
          document.getElementById('widgetCarrito').classList.add('show');
        }
      } catch (error) {
        console.error('Error:', error);
      }
    }
  
    // Función para refrescar el carrito
    async function refreshCart() {
      const response = await fetch('/?sections=cart-section');
      const data = await response.json();
      
      // Shopify 2.0+ usa sections para actualizaciónes AJAX
      const parser = new DOMParser();
      const html = parser.parseFromString(data['cart-section'], 'text/html');
      const newCart = html.querySelector('#widgetCarrito');
      
      if (newCart) {
        document.getElementById('widgetCarrito').outerHTML = newCart.outerHTML;
      }
    }
  });