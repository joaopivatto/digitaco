import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { Card } from '../../components/card/card';
import { RouterLink } from '@angular/router';
import { LeaguesController } from '../../controllers/leagues';
import { myLeague } from '../leagues/leagues';
import { ListaIdiomas } from '../../entities/languages';
import { MessageService } from 'primeng/api';
import { DialogModule } from 'primeng/dialog';
import { TagModule } from 'primeng/tag';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { Router } from '@angular/router';
import { LoadingService } from '../../services/loading.service';

@Component({
  selector: 'app-home',
  imports: [Header, Card, RouterLink, DialogModule, TableModule, ButtonModule, TagModule],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {
  constructor(private leaguesController: LeaguesController, private messageService: MessageService, private router: Router, private loadingService: LoadingService) {}

  myLeagues: myLeague[] = [];
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
    if(this.myLeagues.length == 0) {
      this.messageService.add({ severity: 'info', summary: 'Info', detail: 'Você não está em nenhuma liga. Crie uma ou junte-se a uma existente.' });
      return;
    } else {
      this.visible = true;
    }
  }

  play(league: myLeague) {
    this.router.navigate(['/game', league.id]);
  }
}
