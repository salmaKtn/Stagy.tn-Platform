import { Component, OnInit } from '@angular/core';
import { FeatherIconComponent } from '../../../../shared/components/feather-icon/feather-icon.component';
import { ActivatedRoute } from '@angular/router';
import { NgIf } from '@angular/common';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../../../services/auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';



@Component({
  selector: 'app-simple',
  templateUrl: './register-simple.component.html',
  styleUrls: ['./register-simple.component.scss'],
  imports: [FeatherIconComponent, NgIf, ReactiveFormsModule, CommonModule]
})
export class RegisterSimpleComponent implements OnInit {
  userType: any;
  show: boolean = false;
  registerForm!: FormGroup;
    isRegisterModalOpen = true; // open by default or false to start closed



  constructor(private router: Router, private route: ActivatedRoute,
    private fb: FormBuilder, private authService: AuthService,) { }
 
openRegisterModal() {
  this.isRegisterModalOpen = true;
}

closeRegisterModal() {
  this.isRegisterModalOpen = false;
}
  ngOnInit() {

    this.userType = this.route.snapshot.params['type']
    this.buildForm();
    this.isRegisterModalOpen = true; // open by default or false to start closed

  }

  buildForm() {
    if (this.userType === 'student') {
      this.registerForm = this.fb.group({
        username: ['', [Validators.required, Validators.minLength(3)]],
        birthday: ['', Validators.required],
        gender: ['', Validators.required],
        phoneNumber: ['', Validators.required],
        email: ['', [Validators.required, Validators.email]],
        password: ['', Validators.required],
      });
    } else {
      this.registerForm = this.fb.group({
        username: ['', Validators.required],
        location: ['', Validators.required],
        taxNumber: ['', Validators.required],
        agentName: ['', Validators.required],
        agentLastname: ['', Validators.required],
        agentEmail: ['', [Validators.required]],
        password: ['', Validators.required],
        
      });
    }
  }

  showPassword() {
    this.show = !this.show;
  }

  submitForm() {
    if (this.registerForm.invalid) {
      console.log('invalid');
      this.registerForm.markAllAsTouched(); 
      return;
    }

    if (this.userType === 'student') {
      this.authService.registerStudent(this.registerForm.value).subscribe({
        next: (res) => {
          console.log('Student registered successfully', res);
          this.router.navigate(['/auth/login']);
        },
        error: (err) => {
          console.error('Error registering student', err);
        },
      });
    } else {
      this.authService.registerCompany(this.registerForm.value).subscribe({
        next: (res) => {
          console.log('Company registered successfully', res);
          this.router.navigate(['/auth/login']);
        },
        error: (err) => {
          console.error('Error registering company', err);
        },
      });
    }
  }

}