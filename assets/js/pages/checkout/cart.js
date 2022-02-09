import "../../../css/pages/checkout/cart.scss";

const quantityInput = document.querySelector('.quantity-input');
const uniquePirceWidget = document.getElementById('uniquePrice');
const totalPriceWiget = document.getElementById('totalPrice');

updatePrice(quantityInput.value);
quantityInput.addEventListener('change', e => {
    updatePrice(e.target.value);
});

function updatePrice(quantity){
    totalPriceWiget.innerText = uniquePirceWidget.innerText * quantity;
}
