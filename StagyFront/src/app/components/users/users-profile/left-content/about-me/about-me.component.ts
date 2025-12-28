import { Component, OnInit } from '@angular/core';
import { NgbCollapse } from '@ng-bootstrap/ng-bootstrap';
import { UserService } from '../../../../../services/user.service';

@Component({
    selector: 'app-about-me',
    templateUrl: './about-me.component.html',
    styleUrls: ['./about-me.component.scss'],
    imports: [NgbCollapse]
})
export class AboutMeComponent implements OnInit {
  
  public isProfile = false;

  constructor(private userService :UserService) {}
company:any
  ngOnInit(): void {
    this.userService.getCompany().subscribe({
      next: (data) => {
        this.company = data;
      },
      error: (err) => {
        console.error('Error loading company data:', err);
      }
    });
  
  }

}
