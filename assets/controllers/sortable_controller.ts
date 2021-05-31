import Sortable, { MultiDrag, Swap } from 'sortablejs';
import { Controller } from 'stimulus';

Sortable.mount(new MultiDrag(), new Swap());

export default class extends Controller {
    connect() {
        const element: HTMLElement = this.element as HTMLElement;
        Sortable.create(element, {
            onSort: () => {
                const all = this.element.querySelectorAll('input[id$="_position"]');
                let position = 1;
                all.forEach((sub: HTMLInputElement) => {
                    sub.value = position.toString();
                    position++;
                });
            },
        });
    }
}
