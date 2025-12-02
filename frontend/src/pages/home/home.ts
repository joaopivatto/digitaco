import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { Card } from '../../components/card/card';
import { RouterLink } from '@angular/router';
import { LeaguesController } from '../../controllers/leagues';
import { myLeague } from '../leagues/leagues';
import { ListaIdiomas, IIdiomaDetalhe } from '../../entities/languages';
import { MessageService } from 'primeng/api';
import { DialogModule } from 'primeng/dialog';
import { TagModule } from 'primeng/tag';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { Router } from '@angular/router';
import { LoadingService } from '../../services/loading.service';
import { MultiSelectModule } from 'primeng/multiselect';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-home',
  imports: [
    Header,
    Card,
    RouterLink,
    DialogModule,
    TableModule,
    ButtonModule,
    TagModule,
    MultiSelectModule,
    FormsModule,
  ],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {
  constructor(
    private leaguesController: LeaguesController,
    private messageService: MessageService,
    private router: Router,
    private loadingService: LoadingService
  ) {}

  myLeagues: myLeague[] = [];
  languagesList = ListaIdiomas;
  languages: IIdiomaDetalhe[] = [];

  visible: boolean = false;
  columns = [
    { field: 'name', header: 'Nome da Liga' },
    { field: 'points', header: 'Pontos' },
    { field: 'languages', header: 'Idiomas' },
    { field: 'play', header: 'Jogar' },
  ];

  ngOnInit() {
    this.init();
  }

  async init() {
    this.loadingService.start();
    const myLeagues = await this.leaguesController.getLeaguesUserIsIncluded();
    this.myLeagues = myLeagues.map((league) => ({
      ...league,
      points: league.points ?? 0,
      languages: league.languages.map((language) => ({
        id: language,
        name: ListaIdiomas.find((idioma) => idioma.id === language)?.title || language,
      })),
    }));
    this.loadingService.stop();
  }

  chooseLeague() {
    this.visible = true;
  }

  play(league?: myLeague) {
    if (league) {
      this.router.navigate(['/game', league.id]);
    } else {
      if(this.languages.length === 0) {
        this.messageService.add({ severity: 'error', summary: 'Erro', detail: 'Selecione pelo menos um idioma' });
        return;
      }
      const languages = this.languages.map((l) => l.id).join(',');
      this.router.navigate(['/game'], { queryParams: { languages } });
    }
  }
}
