declare var SecureFieldsClient: any;

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

const CREFOPAY_CONFIG = {
    url: "https://sandbox.crefopay.de/secureFields/",
    placeholders: {
        accountHolder: "Your Name",
        number: "0123456789101112",
        cvv: "000"
    }
};

export default class CrefopayFormLoader extends Component {
    protected crefoPayScriptLoader: ScriptLoader;
    protected secureFieldsClientInstance: any;
    protected configuration: object;

    protected readyCallback(): void {
        this.crefoPayScriptLoader = <ScriptLoader>this.querySelector(`.${this.jsName}__script-loader`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.crefoPayScriptLoader.addEventListener('scriptload', () => this.onScriptLoad());
    }

    protected onScriptLoad(): void {
        this.secureFieldsClientInstance =
            new SecureFieldsClient(
                this.crefopayShopPublicKey,
                this.crefopayOrderId,
                this.paymentRegisteredCallback,
                this.initializationCompleteCallback,
                CREFOPAY_CONFIG);
        this.secureFieldsClientInstance.registerPayment();
    }

    protected paymentRegisteredCallback(response) {
        if (response.resultCode === 0) {
            // Successful registration, continue to next page using JavaScript
        } else {
            // Error during registration, check the response for more details and dynamically show a message for the customer
        }
    }

    protected initializationCompleteCallback(response) {
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
}
