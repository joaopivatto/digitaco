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
import { Router, RouterLink } from '@angular/router';
import { UsersController } from '../../controllers/users';
import { LoadingService } from '../../services/loading.service';
import { MatchesController, UserHistoryOutput } from '../../controllers/matches';

@Component({
  selector: 'app-header',
  imports: [ButtonModule, RouterModule, TooltipModule, AsyncPipe, ConfirmDialogModule, RouterLink],
  providers: [ConfirmationService],
  templateUrl: './header.html',
  styleUrl: './header.scss',
})
export class Header {
  public user$: Observable<User | null>;
  constructor(
    private userService: UserService,
    private confirmationService: ConfirmationService,
    private messageService: MessageService,
    private router: Router,
    private userController: UsersController,
    private loading: LoadingService,
    private matchesController: MatchesController,
  ) {
    this.user$ = this.userService.currentUser;
  }
  score: number = 2300;

  matches: UserHistoryOutput = {
    message: '',
    userPerformance: {
      totalMatches: 0,
      totalPoints: 0,
      totalWords: 0,
      bestScore: 0,
      matches: [],
    },
  };

  async ngOnInit() {
    this.matches = await this.matchesController.getUserHistory();
  }

  async confirmLogout(event: Event) {
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
      accept: async () => {
        try {
          this.loading.start();
          await this.userController.logOut();
          this.userService.clearUsuario();
          this.router.navigate(['/login']);
          this.messageService.add({
            severity: 'info',
            summary: 'Logout',
            detail: 'Você saiu com sucesso',
            life: 3000,
          });
        } catch (error) {
          this.messageService.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Ocorreu um erro ao sair',
            life: 3000,
          });
        } finally {
          this.loading.stop();
        }
      },
      reject: () => {},
    });
  }
}
