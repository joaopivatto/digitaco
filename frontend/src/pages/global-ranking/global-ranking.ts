import { Component } from '@angular/core';
import { Header } from '../../components/header/header'
import { Button } from 'primeng/button';
import { RouterLink } from '@angular/router';
import { TabsModule } from 'primeng/tabs';
import { MatchesController } from '../../controllers/matches';
import { Rating } from '../../entities/rating';
import { ScrollerModule } from 'primeng/scroller';

@Component({
  selector: 'app-global-ranking',
  standalone: true,
  imports: [Header, Button, RouterLink, TabsModule, ScrollerModule],
  templateUrl: './global-ranking.html',
  styleUrl: './global-ranking.scss',
})
export class GlobalRanking {
  constructor(private matchesController: MatchesController) {}

  ratings: Rating[] = [];
  lastWeekRatings: Rating[] = [];

  async ngOnInit() {
    this.ratings = await this.matchesController.getGlobalRating(false);
    this.lastWeekRatings = await this.matchesController.getGlobalRating(true);
  }
}
