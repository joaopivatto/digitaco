import { Component, inject } from '@angular/core';
import { Header } from '../../components/header/header'
import { ButtonModule } from 'primeng/button';
import { RouterModule } from '@angular/router';
import { UserService } from '../../services/user.service';
import { Observable } from 'rxjs';
import { User } from '../../entities/user';
import { AsyncPipe } from '@angular/common';
import { AvatarModule } from 'primeng/avatar';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { UsersController } from '../../controllers/users';
import { InputComponent } from '../../components/input/input';
import { MessageService } from 'primeng/api';
import { ToastModule } from 'primeng/toast';
import { TooltipModule } from 'primeng/tooltip';



@Component({
  selector: 'app-user-settings',
  standalone: true,
  imports: [Header, ButtonModule, RouterModule, AsyncPipe, AvatarModule, InputComponent, FormsModule, ReactiveFormsModule, ToastModule, TooltipModule],
  providers: [MessageService],
  templateUrl: './user-settings.html',
  styleUrl: './user-settings.scss',
})
export class UserSettings {
  public user$: Observable<User | null>;
  constructor(private userService: UserService, private usersController: UsersController, private messageService: MessageService) {
    this.user$ = this.userService.currentUser;
  }
  private formBuilder = inject(FormBuilder);

  changePasswordForm = this.formBuilder.group({
    newPassword: ['', [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
    confirmNewPassword: ['', [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
  });
  active: string = 'user-info';

  async onSubmit() {
    if (this.changePasswordForm.valid) {
      const newPassword = this.changePasswordForm.value.newPassword as string;
      const confirmNewPassword = this.changePasswordForm.value.confirmNewPassword as string;
      if (newPassword !== confirmNewPassword) {
        this.messageService.add({ severity: 'error', summary: 'Erro', detail: 'As senhas não coincidem' });
        return;
      }
      try {
        await this.usersController.changePassword({
          email: this.userService.getUsuario()?.email as string,
          password: newPassword,
          confirmPassword: confirmNewPassword,
        });
        this.messageService.add({ severity: 'success', summary: 'Sucesso', detail: 'Senha alterada com sucesso' });
      } catch (error) {
        console.error('Erro ao alterar senha', error);
        this.messageService.add({ severity: 'error', summary: 'Erro', detail: 'Erro ao alterar senha' });
      }
      this.changePasswordForm.reset();
    }
  }
}
