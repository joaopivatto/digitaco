import { Component, inject } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { FormsModule, FormBuilder, Validators, ReactiveFormsModule } from '@angular/forms';
import { MessageService } from 'primeng/api';
import { RouterLink, Router } from '@angular/router';
import { ToastModule } from 'primeng/toast';


@Component({
  selector: 'app-sign-up',
  standalone: true,
  imports: [InputComponent, Button, FormsModule, ReactiveFormsModule, RouterLink, ToastModule],
  providers: [MessageService],
  templateUrl: './sign-up.html',
  styleUrl: './sign-up.scss',
})
export class SignUp {
  constructor(private messageService: MessageService, private router: Router) {}


  private formBuilder = inject(FormBuilder);
  protected signUpForm = this.formBuilder.group({
    email: [null, [Validators.required, Validators.email]],
    password: [null, [Validators.required, Validators.minLength(6)]],
    confirmPassword: [null, [Validators.required, Validators.minLength(6)]]
  });


  onSubmit() {
    console.log(this.signUpForm.value);
    if (this.signUpForm.value.password !== this.signUpForm.value.confirmPassword) {
      this.messageService.add({
        severity: 'error',
        summary: 'Error',
        detail: 'As senhas não coincidem',
      });
      return;
    }
    if (this.signUpForm.valid) {
      this.router.navigate(['/home']);
    }
  }
}
