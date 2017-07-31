import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from "@angular/router";
import {Profile} from "../../Entity/Profile";
import {ProfileService} from "../../Service/ProfileService";

@Component({
    templateUrl: './template.pug',
    styleUrls: ['./style.shadow.scss']
})

export class ProfileAvatarRoute implements OnInit {
    public profile: Profile;
    
    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private profileService: ProfileService
    ) {}

    ngOnInit() {
        this.profile = this.route.snapshot.parent.data['profile'];

        if(!this.profileService.hasAvatar(this.profile)) {
            this.router.navigate(["not-found"]);
        }
    }

    public isActive: boolean = false;
    public likeCount: number = 15;

    toggleLike() {
         this.likeCount += (this.isActive ? -1 : 1);
        this.isActive = !this.isActive;
    }
    
    public goProfilePage() {
        this.router.navigate(["profile", this.profile.alias || this.profile.id])
    }
}