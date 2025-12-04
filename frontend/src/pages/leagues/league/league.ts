import { Component, OnInit } from '@angular/core';
import { Header } from '../../../components/header/header';
import { ButtonModule } from 'primeng/button';
import { RouterModule, ActivatedRoute } from '@angular/router';
import { Router } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { FluidModule } from 'primeng/fluid';
import { ScrollerModule } from 'primeng/scroller';
import { TagModule } from 'primeng/tag';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ConfirmationService, MessageService } from 'primeng/api';
import { LoadingService } from '../../../services/loading.service';
import { LeaguesController } from '../../../controllers/leagues';
import { League as LeagueModel } from '../../../entities/league';
import { ListaIdiomas } from '../../../entities/languages';

@Component({
  selector: 'app-league',
  imports: [
    Header,
    ButtonModule,
    RouterModule,
    TabsModule,
    FluidModule,
    ScrollerModule,
    TagModule,
    ConfirmDialogModule,
  ],
  templateUrl: './league.html',
  styleUrl: './league.scss',
  providers: [ConfirmationService, MessageService],
})
export class League implements OnInit {
  constructor(
    private loadingService: LoadingService,
    private leaguesController: LeaguesController,
    private route: ActivatedRoute,
    private router: Router,
    private confirmationService: ConfirmationService,
    private messageService: MessageService
  ) {}
  league: LeagueModel = {} as LeagueModel;
  leagues: LeagueModel[] = [];
  createdLeagues: LeagueModel[] = [];
  creator: boolean = false;
  points: number = 0;
  getLanguageName(language: string) {
    return ListaIdiomas.find((idioma) => idioma.id === language)?.title || language;
  }

  async ngOnInit(): Promise<void> {
    this.loadingService.start();
    this.league = await this.leaguesController.findById(this.route.snapshot.params['id']);
    this.leagues = await this.leaguesController.getLeaguesUserIsIncluded();
    this.createdLeagues = await this.leaguesController.getLeaguesUserIsCreator();
    const leaguePoints = this.leagues.find((league) => league.id === this.league.id)?.points || 0;
    this.points = leaguePoints;
    this.creator = this.createdLeagues.some((league) => league.id === this.league.id);
    this.loadingService.stop();
  }

  confirmDelete(event: Event) {
    this.confirmationService.confirm({
      target: event.target as EventTarget,
      message: 'Deseja excluir esta liga? Esta ação não pode ser desfeita.',
      header: 'Confirmação',
      icon: 'pi pi-exclamation-triangle',
      rejectButtonProps: {
        label: 'Cancelar',
        severity: 'secondary',
        outlined: true,
      },
      acceptButtonProps: {
        label: 'Excluir',
        severity: 'danger',
      },
      accept: async () => {
        try {
          this.loadingService.start();
          await this.leaguesController.deleteLeague(this.league.id);
          this.messageService.add({
            severity: 'success',
            summary: 'Sucesso',
            detail: 'Liga excluída com sucesso',
          });
          this.router.navigate(['/leagues']);
        } catch (error: any) {
          this.messageService.add({
            severity: 'error',
            summary: 'Erro',
            detail: error?.message || 'Falha ao excluir a liga',
          });
        } finally {
          this.loadingService.stop();
        }
      },
    });
  }
}
