import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, Validators, FormsModule, ReactiveFormsModule } from "@angular/forms";
import { Router, RouterLink } from "@angular/router";
import {  } from "@angular/common";
import { FeatherIconComponent } from "../../../../shared/components/feather-icon/feather-icon.component";
import { RouterModule } from '@angular/router'; 

@Component({
    selector: "app-login",
    templateUrl: "./validation.component.html",
    styleUrls: ["./validation.component.scss"],
    imports: [
        FormsModule,
        ReactiveFormsModule,
        FeatherIconComponent,
        RouterModule ,
    ]
})
export class LoginComponent implements OnInit {
  public show: boolean = false;
  public loginForm: FormGroup;
  public errorMessage: any;

  constructor(private fb: FormBuilder, public router: Router) {
    this.loginForm = this.fb.group({
      username: ["your username", [Validators.required]],
      password: ["test123", Validators.required],
    });
  }
  ngOnInit(): void {
  }
  showPassword() {
    this.show = !this.show;
  }
   login() {
    if (this.loginForm.value["username"] == "your username" && this.loginForm.value["password"] == "test123") {
      let user = {
        username: "your username",
        password: "test123",
        name: "test user",
      };
      localStorage.setItem("user", JSON.stringify(user));
      this.router.navigate(["/dashboard/default"]);
    }
  }

}
