
import Component from 'ShopUi/models/component';

export default class CrefopayCheckboxHelper extends Component {

    protected checkboxContainer: HTMLElement;
    protected trigger: any;
    protected target: any;

    protected readyCallback(): void {
        this.checkboxContainer = <HTMLElement>this.closest(this.containerSelector);
        this.trigger = <HTMLElement>this.checkboxContainer.querySelector(this.triggerSelector);
        this.target = this.checkboxContainer.querySelector(this.targetSelector);

        this.applyAttributes();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onChange(event), true);
    }

    protected applyAttributes (): void {
        console.log(this.target);
        this.target.setAttribute('name', this.nameAttribute);
        this.target.setAttribute(this.customAttrName, this.customAttrValue);
    }

    protected onChange(event: Event): void {
        this.target.checked = this.trigger.checked;
    }

    get triggerSelector() {
        return this.getAttribute('trigger-selector');
    }

    get containerSelector() {
        return this.getAttribute('container-selector');
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

    get nameAttribute() {
        return this.getAttribute('name');
    }
}
