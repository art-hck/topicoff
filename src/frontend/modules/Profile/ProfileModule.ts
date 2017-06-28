import {NgModule} from "@angular/core";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";

import {CommonModule} from "../Common/CommonModule";
import {ProfileRoute} from "./Route/ProfileRoute/index";
import {ProfileSettingsRoute} from "./Route/ProfileSettingsRoute/index";
import {ProfileRESTService} from "./Service/ProfileRESTService";
import {ProfileResolver} from "./Service/ProfileResolver";
import {ProfileService} from "./Service/ProfileService";
import {ProfileTitleResolver} from "./Service/ProfileTitleResolver";
import {ProfileDateCreatedPipe} from "./Pipe/ProfileDateCreatedPipe";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        ReactiveFormsModule
    ],
    declarations: [
        ProfileRoute,
        ProfileSettingsRoute,
        ProfileDateCreatedPipe
    ],
    providers: [
        ProfileRESTService,
        ProfileService,
        ProfileResolver,
        ProfileTitleResolver
    ]
})
export class ProfileModule {
} 