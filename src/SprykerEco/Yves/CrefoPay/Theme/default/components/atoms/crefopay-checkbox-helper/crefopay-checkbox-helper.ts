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

    protected applyAttributes (): void {
        this.targets.forEach((target: HTMLInputElement)=>{
            target.setAttribute(this.customAttrName, this.customAttrValue);
        })
    }

    protected onChange(event: Event): void {
        this.targets.forEach((target: HTMLInputElement)=>{
            target.checked = false;
        });
        this.syncCheckboxes(event.currentTarget);
    }

    protected syncCheckboxes(triggered: EventTarget): void {
        let jointContainer = (<HTMLElement>triggered).closest(this.jointContainerSelector);
        let triggeredTarget: HTMLInputElement = jointContainer.querySelector(this.targetSelector);
        triggeredTarget.checked = true;
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

    get customAttrName() {
        return this.getAttribute('custom-attr-name');
    }

    get customAttrValue() {
        return this.getAttribute('custom-attr-value');
    }

    get jointContainerSelector() {
        return this.getAttribute('joint-container-selector');
    }
}
