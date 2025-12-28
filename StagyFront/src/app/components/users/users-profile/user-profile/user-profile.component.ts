import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { UserService } from '../../../../services/user.service';


@Component({
    selector: 'app-user-profile',
    templateUrl: './user-profile.component.html',
    styleUrls: ['./user-profile.component.scss'],
    imports: [RouterLink]
})
export class UserProfileComponent implements OnInit {
  companyData: any;
  constructor(private userService: UserService) {}

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


