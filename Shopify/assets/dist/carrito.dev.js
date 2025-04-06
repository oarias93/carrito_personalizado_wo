"use strict";

document.addEventListener('DOMContentLoaded', function () {
  // Toggle del carrito
  document.querySelector('.toggle-button').addEventListener('click', function () {
    document.getElementById('widgetCarrito').classList.toggle('show');
  }); // Delegación de eventos para los botones

  document.getElementById('widgetCarrito').addEventListener('click', function (e) {
    var target = e.target;
    var cartItem = target.closest('.widgetCarrito__item');
    if (!cartItem) return;
    var itemKey = cartItem.dataset.key;
    var currentQty = parseInt(cartItem.querySelector('.cantidad').textContent);
    var newQty = currentQty; // Manejar aumento/disminución de cantidad

    if (target.classList.contains('cantidad-boton')) {
      var action = target.dataset.action;
      newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
      updateCartItem(itemKey, newQty);
    } // Manejar eliminación de item


    if (target.classList.contains('eliminar')) {
      updateCartItem(itemKey, 0);
    }
  }); // Función para actualizar el carrito

  function updateCartItem(key, quantity) {
    var wasVisible, response, cart;
    return regeneratorRuntime.async(function updateCartItem$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            wasVisible = document.getElementById('widgetCarrito').classList.contains('show');
            _context.prev = 1;
            _context.next = 4;
            return regeneratorRuntime.awrap(fetch('/cart/change.js', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                id: key,
                quantity: quantity
              })
            }));

          case 4:
            response = _context.sent;
            _context.next = 7;
            return regeneratorRuntime.awrap(response.json());

          case 7:
            cart = _context.sent;
            refreshCart(cart);

            if (wasVisible) {
              document.getElementById('widgetCarrito').classList.add('show');
            }

            _context.next = 15;
            break;

          case 12:
            _context.prev = 12;
            _context.t0 = _context["catch"](1);
            console.error('Error:', _context.t0);

          case 15:
          case "end":
            return _context.stop();
        }
      }
    }, null, null, [[1, 12]]);
  } // Función para refrescar el carrito


  function refreshCart() {
    var response, data, parser, html, newCart;
    return regeneratorRuntime.async(function refreshCart$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            _context2.next = 2;
            return regeneratorRuntime.awrap(fetch('/?sections=cart-section'));

          case 2:
            response = _context2.sent;
            _context2.next = 5;
            return regeneratorRuntime.awrap(response.json());

          case 5:
            data = _context2.sent;
            // Shopify 2.0+ usa sections para actualizaciónes AJAX
            parser = new DOMParser();
            html = parser.parseFromString(data['cart-section'], 'text/html');
            newCart = html.querySelector('#widgetCarrito');

            if (newCart) {
              document.getElementById('widgetCarrito').outerHTML = newCart.outerHTML;
            }

          case 10:
          case "end":
            return _context2.stop();
        }
      }
    });
  }
});