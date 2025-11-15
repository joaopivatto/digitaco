import { Component, inject } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { RouterLink } from '@angular/router';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [ToastModule, InputComponent, FormsModule, Button, RouterLink, ReactiveFormsModule],
  providers: [MessageService],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  constructor(private messageService: MessageService) {}
  label: string = 'Texto';

  private formBuilder = inject(FormBuilder);
  protected loginForm = this.formBuilder.group({
    email: [null, [Validators.required, Validators.email]],
    password: [null, [Validators.required, Validators.minLength(6)]],
  });

  onSubmit() {
    console.log(this.loginForm);
    if (this.loginForm.valid) {
      console.log(this.loginForm.value);
    } else {
      this.messageService.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Formulário inválido',
      });
    }
  }
}
