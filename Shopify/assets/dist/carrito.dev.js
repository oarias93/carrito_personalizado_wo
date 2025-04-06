"use strict";

document.addEventListener('DOMContentLoaded', function () {
  // Toggle del carrito
  document.addEventListener('click', function (e) {
    if (e.target.closest('.toggle-button')) {
      document.getElementById('widgetCarrito').classList.toggle('show');
    }
  }); // Delegación de eventos para el carrito

  document.addEventListener('click', function _callee(e) {
    var target, cartItem, itemKey, quantityElement, currentQty, newQty, action;
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            target = e.target;
            cartItem = target.closest('.widgetCarrito__item');

            if (cartItem) {
              _context.next = 4;
              break;
            }

            return _context.abrupt("return");

          case 4:
            itemKey = cartItem.dataset.key;
            quantityElement = cartItem.querySelector('.cantidad');
            currentQty = parseInt(quantityElement.textContent);
            newQty = currentQty; // Manejar aumento/disminución de cantidad

            if (!target.classList.contains('cantidad-boton')) {
              _context.next = 15;
              break;
            }

            action = target.dataset.action;
            newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1); // Actualización inmediata en la UI

            quantityElement.textContent = newQty;
            _context.next = 14;
            return regeneratorRuntime.awrap(updateCartItem(itemKey, newQty, cartItem));

          case 14:
            return _context.abrupt("return");

          case 15:
            if (!target.classList.contains('eliminar')) {
              _context.next = 19;
              break;
            }

            _context.next = 18;
            return regeneratorRuntime.awrap(updateCartItem(itemKey, 0, cartItem));

          case 18:
            cartItem.remove();

          case 19:
            // Actualizar subtotal después de cualquier cambio
            updateCartSubtotal();

          case 20:
          case "end":
            return _context.stop();
        }
      }
    });
  }); // Función para formatear moneda (reemplazo de Shopify.formatMoney)

  function formatMoney(cents) {
    return '$' + (cents / 100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  } // Función para actualizar el carrito


  function updateCartItem(key, quantity, itemElement) {
    var response, cart, item;
    return regeneratorRuntime.async(function updateCartItem$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            _context2.prev = 0;
            _context2.next = 3;
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

          case 3:
            response = _context2.sent;
            _context2.next = 6;
            return regeneratorRuntime.awrap(response.json());

          case 6:
            cart = _context2.sent;

            // Actualizar precios si no es eliminación
            if (quantity > 0 && itemElement) {
              item = cart.items.find(function (i) {
                return i.key === key;
              });

              if (item) {
                itemElement.querySelector('.widgetCarrito__price').textContent = formatMoney(item.line_price);
              }
            }

            return _context2.abrupt("return", cart);

          case 11:
            _context2.prev = 11;
            _context2.t0 = _context2["catch"](0);
            console.error('Error al actualizar carrito:', _context2.t0);
            throw _context2.t0;

          case 15:
          case "end":
            return _context2.stop();
        }
      }
    }, null, null, [[0, 11]]);
  } // Función para actualizar el subtotal


  function updateCartSubtotal() {
    var response, cart;
    return regeneratorRuntime.async(function updateCartSubtotal$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            _context3.prev = 0;
            _context3.next = 3;
            return regeneratorRuntime.awrap(fetch('/cart.js'));

          case 3:
            response = _context3.sent;
            _context3.next = 6;
            return regeneratorRuntime.awrap(response.json());

          case 6:
            cart = _context3.sent;
            document.querySelector('.widgetCarrito__subtotal').textContent = "Subtotal: ".concat(formatMoney(cart.total_price));
            _context3.next = 13;
            break;

          case 10:
            _context3.prev = 10;
            _context3.t0 = _context3["catch"](0);
            console.error('Error al actualizar subtotal:', _context3.t0);

          case 13:
          case "end":
            return _context3.stop();
        }
      }
    }, null, null, [[0, 10]]);
  }
});