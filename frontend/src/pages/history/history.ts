import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { CardModule } from 'primeng/card';
import { ButtonModule } from 'primeng/button';
import { TableModule } from 'primeng/table';
import { TagModule } from 'primeng/tag';
import { RouterModule } from '@angular/router';
import { MatchesController, UserHistoryOutput } from '../../controllers/matches';
import { LoadingService } from '../../services/loading.service';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-history',
  standalone: true,
  imports: [Header, CardModule, ButtonModule, RouterModule, TableModule, TagModule],
  templateUrl: './history.html',
  styleUrl: './history.scss',
  providers: [MessageService],
})
export class History {
  constructor(
    private matchesController: MatchesController,
    private loadingService: LoadingService,
    private messageService: MessageService
  ) {}

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
  averagePoints: number = 0;
  wordsPerMatch: number = 0;

  async ngOnInit() {
    try {
      this.loadingService.start();
      this.matches = await this.matchesController.getUserHistory();
      this.averagePoints =
        this.matches.userPerformance.totalPoints / this.matches.userPerformance.totalMatches || 0;
      this.wordsPerMatch =
        this.matches.userPerformance.totalWords / this.matches.userPerformance.totalMatches || 0;
    } catch (error: any) {
      this.messageService.add({
        severity: 'error',
        summary: 'Erro',
        detail: error.message || 'Erro ao carregar histórico',
      });
    } finally {
      this.loadingService.stop();
    }
  }
}
