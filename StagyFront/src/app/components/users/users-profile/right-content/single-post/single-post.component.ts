import { Component, OnInit, Input } from '@angular/core';
import { UserService } from '../../../../../services/user.service';


@Component({
    selector: 'app-single-post',
    templateUrl: './single-post.component.html',
    styleUrls: ['./single-post.component.scss'],
    imports: [],
})
export class SinglePostComponent implements OnInit {

  @Input() data: any
  companyData : any

  constructor(private userService: UserService) {
    this.userService.getCompany().subscribe({
      next: (data) => {
        this.companyData = data;
      },
      error: (err) => {
        console.error('Error loading company data:', err);
      }
    });
    
   }

  ngOnInit(): void {
  }

}
