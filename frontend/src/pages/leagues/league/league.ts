import { Component, OnInit } from '@angular/core';
import { Header } from '../../../components/header/header';
import { ButtonModule } from 'primeng/button';
import { RouterModule, ActivatedRoute } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { FluidModule } from 'primeng/fluid';
import { ScrollerModule } from 'primeng/scroller';
import { LoadingService } from '../../../services/loading.service';
import { LeaguesController } from '../../../controllers/leagues';
import { League as LeagueModel } from '../../../entities/league';


@Component({
  selector: 'app-league',
  imports: [
    Header,
    ButtonModule,
    RouterModule,
    TabsModule,
    FluidModule,
    ScrollerModule,
  ],
  templateUrl: './league.html',
  styleUrl: './league.scss',
})
export class League implements OnInit {
  constructor(private loadingService: LoadingService, private leaguesController: LeaguesController, private route: ActivatedRoute) {}
  league: LeagueModel = {} as LeagueModel;
  

  async ngOnInit(): Promise<void> {
    this.loadingService.start();
    this.league = await this.leaguesController.findById(this.route.snapshot.params['id']);
    this.loadingService.stop();
  }
}
