import {Component, EventEmitter, Input, Output} from '@angular/core';
import {ModalHeaderComponent} from "./layout/ModalHeaderComponent/index";
import {ModalBodyComponent} from "./layout/ModalBodyComponent/index";
import {ModalFooterComponent} from "./layout/ModalFooterComponent/index";

@Component({
    selector: 'modal',
    templateUrl: './template.pug',
    host: {'(window:keydown)': 'onKeyDown($event)'},
    styleUrls: ['./style.shadow.scss']
})

export class ModalComponent {
    @Input('width') width: string = 'auto';
    @Input('height') height: string = 'auto';
    @Input('backdrop') backdrop: boolean = true;
    @Input('can-close') canClose: boolean = true;
    @Output('on-close') onClose = new EventEmitter<any>();

    private onKeyDown($event: KeyboardEvent) {
        if ($event.key === "Escape" && this.canClose) {
            this.close()
        }
    }

    public close() {
        this.onClose.emit();
    }
}

export const ModalComponents = [
    ModalComponent,
    ModalHeaderComponent,
    ModalFooterComponent,
    ModalBodyComponent
];