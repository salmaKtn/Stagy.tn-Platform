import { Component, OnInit } from '@angular/core';
import { FriendsComponent } from './friends/friends.component';
import { LatestPhotosComponent } from './latest-photos/latest-photos.component';
import { FollowingsComponent } from './followings/followings.component';
import { FollowersComponent } from './followers/followers.component';
import { AboutMeComponent } from './about-me/about-me.component';
import { UserService } from '../../../../services/user.service';

@Component({
    selector: 'app-left-content',
    templateUrl: './left-content.component.html',
    styleUrls: ['./left-content.component.scss'],
    imports: [AboutMeComponent, FollowersComponent, FollowingsComponent, LatestPhotosComponent, FriendsComponent,]
})
export class LeftContentComponent implements OnInit {

  constructor(private userService: UserService) { }
 companyData:any
  ngOnInit(): void {
    this.userService.getCompany().subscribe({
      next: (data) => {
        this.companyData = data;
      },
      error: (err) => {
        console.error('Error loading company data:', err);
      }
    });
  
  }

}
