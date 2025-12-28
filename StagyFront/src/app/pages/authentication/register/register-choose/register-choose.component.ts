import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register-choose',
  templateUrl: './register-choose.component.html',
  styleUrls: ['./register-choose.component.scss'],
})
export class RegisterChooseComponent {
  constructor(private router: Router) {}

  goToStudentRegister() {
    this.router.navigate(['/register/student']);
  }

  goToCompanyRegister() {
    this.router.navigate(['/register/company']);
  }
}
