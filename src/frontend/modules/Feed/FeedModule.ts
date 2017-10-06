import {NgModule} from "@angular/core";

import {CommonModule} from "../Common/CommonModule";
import {FeedProfileRoute} from "./Route/FeedProfileRoute/index";
import {FeedHotRoute} from "./Route/FeedHotRoute/index";
import {FeedNewRoute} from "./Route/FeedNewRoute/index";
import {FeedBestRoute} from "./Route/FeedBestRoute/index";
import {FeedService} from "./Service/FeedService";
import {FeedRESTService} from "./Service/FeedRESTService";
import {FeedComponent} from "./Component/FeedComponent/index";

@NgModule({
    imports: [
        CommonModule
    ],
    declarations: [
        FeedProfileRoute,
        FeedHotRoute,
        FeedNewRoute,
        FeedBestRoute,

        FeedComponent
    ],
    providers: [
        FeedService,
        FeedRESTService
    ],
    exports: [
        FeedComponent
    ]
})
export class FeedModule {} 