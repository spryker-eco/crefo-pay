declare var SecureFieldsClient: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

export default class CrefopayPaymentForm extends Component {
    protected crefoPayScriptLoader: ScriptLoader;
    protected paymentForm: HTMLFormElement;
    protected paymentInstrumentId: HTMLInputElement;
    protected errorBlock: HTMLElement;
    protected paymentContainer: HTMLElement;
    protected paymentToggler: HTMLElement;
    protected buttons: HTMLButtonElement[];

    protected readyCallback(): void {
        this.paymentForm = <HTMLFormElement>document.querySelector(this.paymentFormSelector);
        this.crefoPayScriptLoader = <ScriptLoader>document.querySelector(`.${this.jsName}__script-loader`);
        this.paymentContainer = <HTMLElement>this.closest(this.paymentContainerSelector);
        this.paymentToggler = <HTMLElement>this.paymentContainer.querySelector(this.paymentTogglerSelector);
        this.buttons = <HTMLButtonElement[]>Array.from(this.paymentForm.getElementsByTagName('button'));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.crefoPayScriptLoader.addEventListener('scriptload', () => this.onScriptLoad());
        this.paymentForm.addEventListener('submit', (event: Event) => this.onSubmit(event));
    }

    protected onSubmit(event: Event): void {
        if(!this.paymentToggler.classList.contains(this.classToCheck)) {
            event.preventDefault();
            document['CrefoPaySecureFieldsClient'].registerPayment();
        }
    }

    protected onScriptLoad(): void {
        if (!document['CrefoPaySecureFieldsClient']) {
            document['CrefoPaySecureFieldsClient'] =
                new SecureFieldsClient(
                    this.crefopayShopPublicKey,
                    this.crefopayOrderId,
                    this.paymentRegisteredCallback.bind(this),
                    this.initializationCompleteCallback,
                    this.crefoPayConfig);
        }
    }

    protected paymentRegisteredCallback(response): void {
        this.findPaymentToggler();
        if (response.resultCode !== 0) {
            this.errorBlock.innerHTML = response.message;
            this.errorBlock.classList.remove(this.classToToggle);
            setTimeout(() => this.enableSubmitButtons(), 0);
            return;
        }

        if (response.paymentInstrumentId) {
            this.paymentInstrumentId.value = response.paymentInstrumentId;
        }

        this.paymentForm.submit();
    }

    protected initializationCompleteCallback(response): void {
        if (response.resultCode !== 0) {
            console.log('initialization failed');
            return;
        }

        console.log('initialization success');
    }

    protected findPaymentToggler(): void {
        const paymentTogglers: HTMLElement[] = Array.from(this.paymentForm.querySelectorAll(this.paymentTogglerSelector));
        paymentTogglers.forEach((toggler: HTMLElement)=>{
            if (!toggler.classList.contains('is-hidden')) {
                this.paymentInstrumentId = toggler.querySelector('[name*="paymentInstrumentId"]');
                this.errorBlock = toggler.querySelector(`.${this.jsName}__error`);
            }
        });
    }

    protected enableSubmitButtons(): void {
        this.buttons.forEach((button) => {
            const isSubmitType: boolean = button.getAttribute('type') == 'submit';
            const isDisabled: boolean = button.hasAttribute('disabled');

            if (isSubmitType && isDisabled) {
                button.removeAttribute('disabled');
            }
        });
    }

    get crefopayShopPublicKey() {
        return this.getAttribute('shop-public-key');
    }

    get crefopayOrderId() {
        return this.getAttribute('order-id');
    }

    get crefoPayConfig() {
        return JSON.parse(this.getAttribute('crefo-pay-config'));
    }

    get paymentFormSelector() {
        return this.getAttribute('payment-form-selector');
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
        return this.getAttribute('toggle-class-to-check');
    }
}
