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
  });
});