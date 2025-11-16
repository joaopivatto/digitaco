import { Component, inject } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { FormsModule, FormBuilder, Validators, ReactiveFormsModule } from '@angular/forms';
import { MessageService } from 'primeng/api';
import { RouterLink, Router } from '@angular/router';
import { ToastModule } from 'primeng/toast';
import { UsersController } from '../../controllers/users';
import { UserService } from '../../services/user.service';


@Component({
  selector: 'app-sign-up',
  standalone: true,
  imports: [InputComponent, Button, FormsModule, ReactiveFormsModule, RouterLink, ToastModule],
  providers: [MessageService],
  templateUrl: './sign-up.html',
  styleUrl: './sign-up.scss',
})
export class SignUp {
  constructor(private messageService: MessageService, private router: Router, private usersController: UsersController, private userService: UserService) {}


  private formBuilder = inject(FormBuilder);
  protected signUpForm = this.formBuilder.group({
    name: [null, [Validators.required]],
    email: [null, [Validators.required, Validators.email]],
    password: [null, [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
    confirmPassword: [null, [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
  });


  async onSubmit() {
    if (this.signUpForm.value.password !== this.signUpForm.value.confirmPassword) {
      this.messageService.add({
        severity: 'error',
        summary: 'Error',
        detail: 'As senhas não coincidem',
      });
      return;
    }
    if (this.signUpForm.valid) {
      try {
        await this.usersController.signUp({
          name: this.signUpForm.value.name!,
          email: this.signUpForm.value.email!,
          password: this.signUpForm.value.password!
        });
        const response = await this.usersController.signIn({
          email: this.signUpForm.value.email!,
          password: this.signUpForm.value.password!
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
          detail: error?.message || 'Erro ao cadastrar usuário',
        });
      }
    }
  }
}
