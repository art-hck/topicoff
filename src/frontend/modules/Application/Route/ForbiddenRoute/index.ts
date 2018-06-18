import {Component} from "@angular/core";

import {PlatformService} from "../../Service/PlatformService";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class ForbiddenRoute {
    constructor(private pl: PlatformService) {}

    ngOnInit() {
        this.pl.setPageStatus(403);
    }
}
