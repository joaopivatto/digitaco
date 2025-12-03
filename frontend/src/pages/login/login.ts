import { Component, inject, OnInit } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { RouterLink, Router } from '@angular/router';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';
import { LoadingService } from '../../services/loading.service';
import { UsersController } from '../../controllers/users';
import { UserService } from '../../services/user.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [ToastModule, InputComponent, FormsModule, Button, RouterLink, ReactiveFormsModule],
  providers: [MessageService],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  constructor(
    private messageService: MessageService,
    private router: Router,
    private usersController: UsersController,
    private userService: UserService,
    private loading: LoadingService
  ) {}
  label: string = 'Texto';

  private formBuilder = inject(FormBuilder);
  protected loginForm = this.formBuilder.group({
    email: [null, [Validators.required, Validators.email]],
    password: [
      null,
      [
        Validators.required,
        Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/),
      ],
    ],
  });

  async onSubmit() {
    if (this.loginForm.valid) {
      try {
        this.loading.start();
        const response = await this.usersController.signIn({
          email: this.loginForm.value.email!,
          password: this.loginForm.value.password!,
        });
        this.userService.setUsuario({
          name: response.name!,
          email: response.email!,
        });
        this.router.navigate(['/home']);
      } catch (error: any) {
        this.messageService.add({
          severity: 'error',
          summary: 'Error',
          detail: error.message,
        });
      } finally {
        this.loading.stop();
      }
    } else {
      this.messageService.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Formulário inválido',
      });
    }
  }
}
