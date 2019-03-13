import Component from 'ShopUi/models/component';

export default class CrefopayCheckboxHelper extends Component {

    protected paymentForm: HTMLElement;
    protected triggers: HTMLElement[];
    protected triggerInput: any;
    protected targets: HTMLInputElement[];

    protected readyCallback(): void {
        this.paymentForm = <HTMLElement>document.querySelector(this.containerSelector);
        this.triggers = <HTMLElement[]>Array.from(this.paymentForm.querySelectorAll(this.triggerSelector));
        this.targets = <HTMLInputElement[]>Array.from(this.paymentForm.querySelectorAll(this.targetSelector));

        this.applyAttributes();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('change', (event: Event) => this.onChange(event));
        });
    }

    protected applyAttributes(): void {
        this.targets.forEach((target: HTMLInputElement) => {
            target.setAttribute(this.customAttributeName, this.customAttributeValue);
        })
    }

    protected onChange(event: Event): void {
        this.targets.forEach((target: HTMLInputElement) => {
            target.checked = false;
        });
        this.checkCheckbox(event.currentTarget);
    }

    protected checkCheckbox(checkboxTrigger: EventTarget): void {
        const jointContainer = (<HTMLElement>checkboxTrigger).closest(this.jointContainerSelector);
        const checkbox: HTMLInputElement = jointContainer.querySelector(this.targetSelector);
        checkbox.checked = true;
    }

    get triggerSelector() {
        return this.getAttribute('trigger-selector');
    }

    get containerSelector() {
        return this.getAttribute('payment-container-selector');
    }

    get targetSelector() {
        return this.getAttribute('target-selector');
    }

    get customAttributeName() {
        return this.getAttribute('custom-attribute-name');
    }

    get customAttributeValue() {
        return this.getAttribute('custom-attribute-value');
    }

    get jointContainerSelector() {
        return this.getAttribute('joint-container-selector');
    }
}
