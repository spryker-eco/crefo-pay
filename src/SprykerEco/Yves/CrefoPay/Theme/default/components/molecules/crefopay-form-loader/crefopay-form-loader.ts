declare var SecureFieldsClient: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

const CREFO_PAY_CONFIG = {
    url: "https://sandbox.crefopay.de/secureFields/",
    placeholders: {
        accountHolder: "Your Name",
        number: "0123456789101112",
        cvv: "000"
    }
};

export default class CrefopayFormLoader extends Component {
    protected crefoPayScriptLoader: ScriptLoader;
    public secureFieldsClient: any;
    protected configuration: object;
    protected paymentForm: HTMLFormElement;
    protected paymentFormSubmitButton: HTMLButtonElement;
    protected paymentInstrumentId: HTMLInputElement;
    protected errorBlock: HTMLElement;

    protected readyCallback(): void {
        this.paymentForm = <HTMLFormElement>document.querySelector(this.paymentFormSelector);
        this.paymentFormSubmitButton = <HTMLButtonElement>this.paymentForm.querySelector('button[type="submit"]');
        this.crefoPayScriptLoader = <ScriptLoader>this.querySelector(`.${this.jsName}__script-loader`);
        this.paymentInstrumentId = <HTMLInputElement>this.querySelector(this.paymentInstrumentIdSelector);
        this.errorBlock = <HTMLElement>this.querySelector(`.${this.jsName}__error`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.crefoPayScriptLoader.addEventListener('scriptload', () => this.onScriptLoad());
        this.paymentFormSubmitButton.addEventListener('click', (event: Event) => this.onSubmitButtonClick(event));
    }

    protected onSubmitButtonClick(event: Event): void {
        event.preventDefault();
        this.secureFieldsClient.registerPayment();
    }

    protected onScriptLoad(): void {
        this.secureFieldsClient =
            new SecureFieldsClient(
                this.crefopayShopPublicKey,
                this.crefopayOrderId,
                this.paymentRegisteredCallback.bind(this),
                this.initializationCompleteCallback,
                CREFO_PAY_CONFIG);
    }

    protected paymentRegisteredCallback(response): void {
        if (response.resultCode === 0) {
            this.paymentInstrumentId.value = response.paymentInstrumentId;
            this.paymentForm.submit();
        } else {
            this.errorBlock.classList.remove(this.classToToggle);
        }
    }

    protected initializationCompleteCallback(response): void {
        if (response.resultCode === 0) {
            // Successful registration, continue to next page using JavaScript
        } else {
            // Error during registration, check the response for more details and dynamically show a message for the customer
        }
    }

    get crefopayShopPublicKey() {
        return this.getAttribute('shop-public-key');
    }

    get crefopayOrderId() {
        return this.getAttribute('order-id');
    }

    get paymentFormSelector() {
        return this.getAttribute('payment-form-selector');
    }

    get paymentInstrumentIdSelector() {
        return this.getAttribute('payment-instrument-id-selector');
    }

    get classToToggle() {
        return this.getAttribute('class-to-toggle');
    }
}
