import {EventEmitter, Injectable, Injector} from "@angular/core";

import {AuthService} from "../../Auth/Service/AuthService";
import {TokenService} from "../../Auth/Service/TokenService";

@Injectable()
export class StartupService {

    private promises: Promise<any>[] = [];

    constructor(private injector: Injector) {}

    init(): Promise<any> {
        if(typeof window != 'undefined') {
            let status: HTMLElement = document.getElementById("statusText");
            let authService: AuthService = this.injector.get(AuthService);
            let tokenService: TokenService = this.injector.get(TokenService);

            if (tokenService.isTokenExist() && tokenService.getTokenExpTime() < 0) { // If token expired wait before get a new
                status.innerText = "Авторизация...";
                let onAuthSuccess = new EventEmitter<void>(); // Отдельный event, т.к. вызываем complete, завершающий его. а нам не нужно что бы основной event завершался
                authService.onAuthSuccess.subscribe(() => onAuthSuccess.complete());

                this.promises.push(onAuthSuccess.toPromise());
            }

            authService.addTokenExpirationSchedule();
        }
        return Promise.all(this.promises);
    }
}