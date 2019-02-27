declare var SecureFieldsClient: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

export default class CrefopayFormLoader extends Component {
    protected crefoPayScriptLoader: ScriptLoader;
    public secureFieldsClient: any;
    protected paymentForm: HTMLFormElement;
    protected paymentInstrumentId: HTMLInputElement;
    protected errorBlock: HTMLElement;
    protected paymentContainer: HTMLElement;
    protected paymentToggler: HTMLElement;

    protected readyCallback(): void {
        this.paymentForm = <HTMLFormElement>document.querySelector(this.paymentFormSelector);
        this.crefoPayScriptLoader = <ScriptLoader>this.querySelector(`.${this.jsName}__script-loader`);
        this.paymentInstrumentId = <HTMLInputElement>this.querySelector(this.paymentInstrumentIdSelector);
        this.errorBlock = <HTMLElement>this.querySelector(`.${this.jsName}__error`);
        this.paymentContainer = <HTMLElement>this.closest(this.paymentContainerSelector);
        this.paymentToggler = <HTMLElement>this.paymentContainer.querySelector(this.paymentTogglerSelector);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.crefoPayScriptLoader.addEventListener('scriptload', () => this.onScriptLoad());
        this.paymentForm.addEventListener('onsubmit', (event: Event) => this.onSubmit(event));
    }

    protected onSubmit(event: Event): void {
        event.preventDefault();
        if(!this.paymentToggler.classList.contains(this.classToCheck)) {
            this.secureFieldsClient.registerPayment();
        }
    }

    protected onScriptLoad(): void {
        this.secureFieldsClient =
            new SecureFieldsClient(
                this.crefopayShopPublicKey,
                this.crefopayOrderId,
                this.paymentRegisteredCallback.bind(this),
                this.initializationCompleteCallback,
                this.crefoPayConfig);
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
            console.log('registration success');
        } else {
            console.log('registration failed');
        }
    }

    get crefopayShopPublicKey() {
        return this.getAttribute('shop-public-key');
    }

    get crefopayOrderId() {
        return this.getAttribute('order-id');
    }

    get crefoPayConfig() {
        return this.getAttribute('secure-fields-library-url');
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

    get paymentContainerSelector() {
        return this.getAttribute('payment-container-selector');
    }

    get paymentTogglerSelector() {
        return this.getAttribute('payment-toggler-selector');
    }

    get classToCheck() {
        return JSON.parse(this.getAttribute('toggle-class-to-check')) ;
    }
}
