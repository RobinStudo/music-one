export class CheckoutPayment{
    constructor(container){
        this.container = container;
        this.buildOptions();
        this.setup();
        this.bindEvents();
    }

    buildOptions(){
        this.options = {
            clientKey: this.container.dataset.clientKey,
            paymentKey: this.container.dataset.paymentKey,
            returnUrl: this.container.dataset.url,
        };
    }

    setup(){
        this.client = Stripe(this.options.clientKey);
        this.elements = this.client.elements({
            clientSecret: this.options.paymentKey,
            appearance: {
                theme: 'night',
                labels: "floating",
                variables: {
                    colorBackground: '#fff',
                    colorText: '#000',
                }
            }
        });
        this.paymentElement = this.elements.create('payment');

        const widget = this.container.querySelector('.payment-widget');
        this.paymentElement.mount(widget);
    }

    bindEvents(){
        this.container.addEventListener('submit', e => {
            e.preventDefault();
           this.payment();
        });
    }

    payment(){
        this.client.confirmPayment({
            elements: this.elements,
            confirmParams: {
                return_url: this.options.returnUrl,
            }
        });
    }
}