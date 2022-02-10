export class CheckoutPayment{
    constructor(container){
        this.container = container;
        this.buildOptions();
        this.setup();
    }

    buildOptions(){
        this.options = {
            clientKey: this.container.dataset.clientKey,
            paymentKey: this.container.dataset.paymentKey,
        };
    }

    setup(){
        this.client = Stripe(this.options.clientKey);
        this.elements = this.client.elements({
            clientSecret: this.options.paymentKey,
        });
        this.paymentElement = this.elements.create('payment');

        const widget = this.container.querySelector('.payment-widget');
        this.paymentElement.mount(widget);
    }
}