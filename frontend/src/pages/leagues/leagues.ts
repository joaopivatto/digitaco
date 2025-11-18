import { Component } from '@angular/core';
import { Header } from '../../components/header/header'
import { ButtonModule } from 'primeng/button';
import { RouterModule } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { FluidModule } from 'primeng/fluid';
import { ScrollerModule } from 'primeng/scroller';
import { OnInit } from '@angular/core';
import { LeaguesController } from '../../controllers/leagues'
import { League } from '../../entities/league';

@Component({
  selector: 'app-leagues',
  standalone: true,
  imports: [Header, ButtonModule, RouterModule, TabsModule, FluidModule, ScrollerModule],
  templateUrl: './leagues.html',
  styleUrl: './leagues.scss',
})
export class Leagues implements OnInit {
  constructor(private leaguesController: LeaguesController) {}

  myLeagues: League[] = [];
  otherLeagues: League[] = [];

  async ngOnInit() {
    // this.myLeagues = await this.leaguesController.getLeaguesUserIsIncluded();
    // this.otherLeagues = await this.leaguesController.findAll();
    this.myLeagues = [{
      id: 1,
      name: 'Liga 1',
      members: 10,
      languages: ['Português', 'Inglês'],
    },
    {
      id: 1,
      name: 'Champions',
      members: 10,
      languages: ['Português', 'Inglês'],
    },
    {
      id: 1,
      name: 'Podres',
      members: 10,
      languages: ['Português', 'Inglês'],
    },
    ];
    this.otherLeagues = await this.leaguesController.findAll();
  }
}
