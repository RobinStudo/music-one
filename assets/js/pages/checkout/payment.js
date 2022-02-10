import "../../../css/pages/checkout/payment.scss";
import {CheckoutPayment} from "../../components/checkout-payment";

const paymentForm = document.getElementById("paymentForm");
new CheckoutPayment(paymentForm);