import { Component } from '@angular/core';
import { AsyncPipe } from '@angular/common';
import { ButtonModule } from 'primeng/button';
import { RouterModule } from '@angular/router';
import { TooltipModule } from 'primeng/tooltip';
import { UserService } from '../../services/user.service';
import { Observable } from 'rxjs';
import { User } from '../../entities/user';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ConfirmationService, MessageService } from 'primeng/api';
import { Router } from '@angular/router';

@Component({
  selector: 'app-header',
  imports: [ButtonModule, RouterModule, TooltipModule, AsyncPipe, ConfirmDialogModule, RouterModule],
  providers: [ConfirmationService, MessageService],
  templateUrl: './header.html',
  styleUrl: './header.scss',
})
export class Header {
  public user$: Observable<User | null>;
  constructor(private userService: UserService, private confirmationService: ConfirmationService, private messageService: MessageService, private router: Router) {
    this.user$ = this.userService.currentUser;
  }
  score: number = 2300;

  confirmLogout(event: Event) {
    this.confirmationService.confirm({
      target: event.target as EventTarget,
      message: 'Deseja realmente sair?',
      header: 'Confirmação',
      closable: true,
      closeOnEscape: true,
      icon: 'pi pi-exclamation-triangle',
      rejectButtonProps: {
        label: 'Cancelar',
        severity: 'secondary',
        outlined: true,
      },
      acceptButtonProps: {
        label: 'Sair',
      },
      accept: () => {
        this.messageService.add({
          severity: 'info',
          summary: 'Confirmed',
          detail: 'You have accepted',
        });
        this.userService.setUsuario(null);
        this.router.navigate(['/login']);
        this.messageService.add({
          severity: 'info',
          summary: 'Logout',
          detail: 'Você saiu com sucesso',
          life: 3000,
        });
      },
      reject: () => {

      },
    });
  }
}
