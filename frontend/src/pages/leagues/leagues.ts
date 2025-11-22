import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { ButtonModule } from 'primeng/button';
import { RouterModule, Router } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { FluidModule } from 'primeng/fluid';
import { ScrollerModule } from 'primeng/scroller';
import { OnInit } from '@angular/core';
import { LeaguesController } from '../../controllers/leagues';
import { League } from '../../entities/league';
import { LoadingService } from '../../services/loading.service';
import { DialogModule } from 'primeng/dialog';
import { NewLeague } from './new-league/new-league';
import { TagModule } from 'primeng/tag';
import { ListaIdiomas } from '../../entities/languages'

type myLeague = Omit<League, 'languages'> & {
  languages: {
    id: string;
    name: string;
  }[];
}

@Component({
  selector: 'app-leagues',
  standalone: true,
  imports: [
    Header,
    ButtonModule,
    RouterModule,
    TabsModule,
    FluidModule,
    ScrollerModule,
    DialogModule,
    NewLeague,
    TagModule,
  ],
  templateUrl: './leagues.html',
  styleUrl: './leagues.scss',
})
export class Leagues implements OnInit {
  constructor(private leaguesController: LeaguesController, private loading: LoadingService, private router: Router) {}

  myLeagues: myLeague[] = [];
  otherLeagues: League[] = [];
  creatingLeague: boolean = false;

  showDialog() {
    this.creatingLeague = true;
  }

  async ngOnInit() {
    this.loading.start();
    try {
      const myLeagues = await this.leaguesController.getLeaguesUserIsIncluded();
      this.myLeagues = myLeagues.map((league) => ({
        ...league,
        languages: league.languages.map((language) => ({
          id: language,
          name: ListaIdiomas.find((idioma) => idioma.id === language)?.title || language,
        })),
      }));
      const allLeagues = await this.leaguesController.findAll();
      this.otherLeagues = allLeagues.filter((league) => !league.included);
    } finally {
      this.loading.stop();
    }
  }

  getLanguageName(language: string) {
    switch (language) {
      case 'en':
        return 'Inglês';
      case 'pt-br':
        return 'Português';
      default:
        return language;
    }
  }

  goToLeague(leagueId: string) {
    this.router.navigate(['/league', leagueId]);
  }
}
