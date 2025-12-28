import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators, FormsModule, ReactiveFormsModule } from "@angular/forms";
import { Router, RouterLink } from "@angular/router";
import {  } from "@angular/common";
import { FeatherIconComponent } from "../../shared/components/feather-icon/feather-icon.component";
import { HttpClient } from '@angular/common/http';
import {jwtDecode} from 'jwt-decode';
import { CommonModule } from '@angular/common';





@Component({
    selector: "app-login",
    templateUrl: "./login.component.html",
    styleUrls: ["./login.component.scss"],
    imports: [
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        RouterLink,
        FeatherIconComponent,
    ]
})
export class LoginComponent implements OnInit {
  public show: boolean = false;
  public loginForm: FormGroup;
  public errorMessage: any;
  isLoginModalOpen = false;

  constructor(private fb: FormBuilder, public router: Router,  private http: HttpClient,
) {
    this.loginForm = this.fb.group({
      username: ["", [Validators.required, Validators.minLength(3)]],
      password: ["", Validators.required],
    });
  }
   openLoginModal() {
    this.isLoginModalOpen = true;
  }
 
  ngOnInit() {    this.isLoginModalOpen = true;
}

  showPassword() {
    this.show = !this.show;
  }

  login() {
    if (this.loginForm.invalid) return;

  const credentials = {
    username: this.loginForm.value["username"],
    password: this.loginForm.value["password"]
  };
  
  this.http.post<{ token: string }>('http://127.0.0.1:8000/api/login-check', credentials)
    .subscribe({
      next: (response) => {
        localStorage.setItem('token', response.token);
        const user = jwtDecode(response.token);
        console.log('Decoded user:', user); // Optional, for debugging
        localStorage.setItem('user', JSON.stringify(user));
        if (JSON.parse(localStorage.getItem('user') ?? 'null').roles.includes('ROLE_STUDENT'))
        { this.router.navigate(["/job-search/cards-view"]);}
        else if (JSON.parse(localStorage.getItem('user') ?? 'null').roles.includes('ROLE_COMPANY'))
        {
          { this.router.navigate(["/dashboard/default"]);}

        }
         else if (JSON.parse(localStorage.getItem('user') ?? 'null').roles.includes('ROLE_ADMIN'))
        {
          { this.router.navigate(["/dashboard/default"]);}

        }

      },
      error: (err) => {
        this.errorMessage = 'Login failed. Please check your credentials.';
        console.error(err);
      }
    });
  }

closeLoginModal() {
    this.isLoginModalOpen = false;
  }

  
}


