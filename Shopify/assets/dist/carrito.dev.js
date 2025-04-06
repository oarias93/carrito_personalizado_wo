"use strict";

document.addEventListener('DOMContentLoaded', function () {
  // Mostrar y ocultar el carrito
  document.querySelector('.toggle-button').addEventListener('click', function () {
    document.getElementById('widgetCarrito').classList.toggle('show');
  }); // Delegación de eventos para los botones de aumentar y disminuir cantidades

  document.getElementById('widgetCarrito').addEventListener('click', function (e) {
    var target = e.target;
    var cartItem = target.closest('.widgetCarrito__item');
    if (!cartItem) return;
    var itemKey = cartItem.dataset.key;
    var currentQty = parseInt(cartItem.querySelector('.cantidad').textContent);
    var newQty = currentQty; // Aumentar o disminuir cantidad

    if (target.classList.contains('cantidad-boton')) {
      var action = target.dataset.action;
      newQty = action === 'increase' ? currentQty + 1 : Math.max(1, currentQty - 1);
      updateCartItem(itemKey, newQty);
    } // Eliminar el artículo


    if (target.classList.contains('eliminar')) {
      updateCartItem(itemKey, 0);
    }
  }); // Función para actualizar el carrito con AJAX

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

            if (!cart.errors) {
              _context.next = 11;
              break;
            }

            alert("Hubo un error al actualizar el carrito");
            return _context.abrupt("return");

          case 11:
            // Refrescamos el carrito con los nuevos datos
            refreshCart(cart); // Si el widget estaba visible, mantenemos el estado visible

            if (wasVisible) {
              document.getElementById('widgetCarrito').classList.add('show');
            }

            _context.next = 18;
            break;

          case 15:
            _context.prev = 15;
            _context.t0 = _context["catch"](1);
            console.error('Error al actualizar el carrito:', _context.t0);

          case 18:
          case "end":
            return _context.stop();
        }
      }
    }, null, null, [[1, 15]]);
  } // Función para refrescar el carrito en el frontend


  function refreshCart(cart) {
    var cartList, subtotalElement;
    return regeneratorRuntime.async(function refreshCart$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            // Actualizamos los elementos del carrito en el frontend
            cartList = document.querySelector('.widgetCarrito__list');
            cartList.innerHTML = ''; // Limpiar los items

            cart.items.forEach(function (item) {
              var li = document.createElement('li');
              li.classList.add('widgetCarrito__item');
              li.dataset.key = item.key; // Crear el contenido HTML del carrito con los nuevos datos

              li.innerHTML = "\n              <h3>".concat(item.product.title, "</h3>\n              <span class=\"widgetCarrito__price\">").concat(item.line_price | money, "</span>\n              <div class=\"widgetCarrito__cantidad\">\n                  <button class=\"cantidad-boton\" data-action=\"decrease\">-</button>\n                  <span class=\"cantidad\">").concat(item.quantity, "</span>\n                  <button class=\"cantidad-boton\" data-action=\"increase\">+</button>\n              </div>\n              <button class=\"eliminar\">Eliminar</button>\n          ");
              cartList.appendChild(li);
            }); // Actualizar el subtotal

            subtotalElement = document.querySelector('.widgetCarrito__subtotal');
            subtotalElement.textContent = "Subtotal: ".concat(cart.total_price | money);

          case 5:
          case "end":
            return _context2.stop();
        }
      }
    });
  }
});