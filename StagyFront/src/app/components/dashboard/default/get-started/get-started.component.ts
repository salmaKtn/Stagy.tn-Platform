import { Component, OnInit } from '@angular/core';
import { UserService } from '../../../../services/user.service';
@Component({
    selector: 'app-get-started',
    templateUrl: './get-started.component.html',
    styleUrls: ['./get-started.component.scss'],
    imports: [],
})
export class GetStartedComponent implements OnInit {

  constructor(private userService:UserService) { }

  ngOnInit(): void {  this.getUsernameFromAPI();

  }

username: string = '';


getUsernameFromAPI() {
  this.userService.getUsername().subscribe({
    next: (data) => {
      // assuming your backend returns { username: "Salma" }
      this.username = data.username;
    },
    error: (err) => {
      console.error('Error fetching username', err);
    }
  });
}
}
