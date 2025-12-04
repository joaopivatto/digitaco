import { Component, OnInit } from '@angular/core';
import { Header } from '../../../components/header/header';
import { ButtonModule } from 'primeng/button';
import { RouterModule, ActivatedRoute } from '@angular/router';
import { Router } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { FluidModule } from 'primeng/fluid';
import { ScrollerModule } from 'primeng/scroller';
import { TagModule } from 'primeng/tag';
import { MultiSelectModule } from 'primeng/multiselect';
import { FormsModule } from '@angular/forms';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ConfirmationService, MessageService } from 'primeng/api';
import { LoadingService } from '../../../services/loading.service';
import { LeaguesController } from '../../../controllers/leagues';
import { League as LeagueModel } from '../../../entities/league';
import { ListaIdiomas, IIdiomaDetalhe } from '../../../entities/languages';

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
    MultiSelectModule,
    FormsModule,
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
  editingLanguages: boolean = false;
  languagesList: IIdiomaDetalhe[] = ListaIdiomas;
  selectedLanguages: IIdiomaDetalhe[] = [];
  originalLanguages: string[] = [];
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
    this.selectedLanguages = (this.league.languages || [])
      .map((id) => this.languagesList.find((l) => l.id === id)!)
      .filter(Boolean);
    this.originalLanguages = [...(this.league.languages || [])];
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

  startEditLanguages() {
    this.editingLanguages = true;
    this.selectedLanguages = (this.league.languages || [])
      .map((id) => this.languagesList.find((l) => l.id === id)!)
      .filter(Boolean);
    this.originalLanguages = [...(this.league.languages || [])];
  }

  cancelEditLanguages() {
    this.editingLanguages = false;
    this.selectedLanguages = this.originalLanguages
      .map((id) => this.languagesList.find((l) => l.id === id)!)
      .filter(Boolean);
  }

  async confirmEditLanguages() {
    try {
      this.loadingService.start();
      const currentIds: string[] = this.selectedLanguages.map((l) => String(l.id));
      const toRemove: string[] = this.originalLanguages.filter((id) => !currentIds.includes(id));
      const toAdd: string[] = currentIds.filter((id) => !this.originalLanguages.includes(id));

      await Promise.all([
        ...toRemove.map((language) =>
          this.leaguesController.deleteLanguage({ leagueId: this.league.id, language })
        ),
        ...toAdd.map((language) =>
          this.leaguesController.insertLanguage({ leagueId: this.league.id, language })
        ),
      ]);

      this.league = { ...this.league, languages: currentIds };
      this.originalLanguages = [...currentIds];
      this.editingLanguages = false;
      this.messageService.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Idiomas atualizados',
      });
    } catch (error: any) {
      this.messageService.add({
        severity: 'error',
        summary: 'Erro',
        detail: error?.message || 'Falha ao atualizar idiomas',
      });
    } finally {
      this.loadingService.stop();
    }
  }
}
