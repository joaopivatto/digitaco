import {
  Component,
  OnInit,
  OnDestroy,
  NgZone,
  ElementRef,
  ViewChild,
  AfterViewInit,
  inject,
} from '@angular/core';
import { TagModule } from 'primeng/tag';
import { ButtonModule } from 'primeng/button';
import { FormsModule } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { ConfirmDialog } from 'primeng/confirmdialog';
import { DialogModule } from 'primeng/dialog';
import { ConfirmationService, MessageService } from 'primeng/api';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { WordsController } from '../../controllers/words';
import { LeaguesController } from '../../controllers/leagues';
import { MatchesController } from '../../controllers/matches';
import { League } from '../../entities/league';
import { LoadingService } from '../../services/loading.service';

type WordType = {
  id: number;
  word: string;
  x: number;
  y: number;
  speed: number;
};

@Component({
  selector: 'app-game',
  imports: [TagModule, ButtonModule, FormsModule, InputTextModule, ConfirmDialog, DialogModule, RouterLink],
  templateUrl: './game.html',
  styleUrl: './game.scss',
  providers: [ConfirmationService, MessageService],
})
export class Game implements OnInit, OnDestroy, AfterViewInit {
  @ViewChild('gameInput') gameInput!: ElementRef;
  private route = inject(ActivatedRoute);

  constructor(
    private ngZone: NgZone,
    private confirmDialogService: ConfirmationService,
    private router: Router,
    private wordsController: WordsController,
    private leaguesController: LeaguesController,
    private loading: LoadingService,
    private matchesController: MatchesController
  ) {}

  inputText: string = '';
  points: number = 0;
  wordsCount: number = 0;
  hearts: number = 3;
  words: WordType[] = [];
  speedFactor = 1;
  league: League | null = null;
  showMatchStats: boolean = false;
  isPlaying: boolean = false;
  matchEnded: boolean = false;

  private wordList: string[] = [];
  private gameInterval: any;
  private spawnInterval: any;
  private nextId = 0;

  async ngOnInit() {
    this.loading.start();
    const leagueId = Number(this.route.snapshot.paramMap.get('leagueId'));
    if(leagueId) {
      this.league = await this.leaguesController.findById(leagueId);
    }
    await this.loadWords();
    this.loading.stop();
    this.startGame();
  }

  ngAfterViewInit() {
    this.gameInput.nativeElement.focus();
  }

  ngOnDestroy() {
    this.stopGame();
  }

  confirmExit() {
    this.stopGame();
    this.confirmDialogService.confirm({
      message: 'Tem certeza que deseja sair?',
      header: 'Confirmação',
      icon: 'pi pi-exclamation-triangle',
      rejectButtonProps: {
        label: 'Voltar',
        severity: 'secondary',
        outlined: true,
      },
      acceptButtonProps: {
        label: 'Sair',
      },
      accept: () => {
        this.router.navigate(['/']);
      },
      reject: () => {
        this.startGame();
        setTimeout(() => {
          this.gameInput.nativeElement.focus();
        });
      },
    });
  }

  startGame() {
    if (this.isPlaying) return;
    this.isPlaying = true;
    this.ngZone.runOutsideAngular(() => {
      const loop = () => {
        if (!this.isPlaying) return;
        this.updatePositions();
        if (this.isPlaying) {
          this.gameInterval = requestAnimationFrame(loop);
        }
      };
      this.gameInterval = requestAnimationFrame(loop);
    });

    this.spawnInterval = setInterval(() => {
      this.spawnWord();
    }, 2000);
  }

  stopGame() {
    this.isPlaying = false;
    if (this.gameInterval) {
      cancelAnimationFrame(this.gameInterval);
    }
    if (this.spawnInterval) {
      clearInterval(this.spawnInterval);
    }
    if(this.matchEnded) {
      this.league?.id && this.matchesController.create({
        leagueId: this.league.id ?? null,
        points: this.points,
        words: this.wordsCount,
      });
      this.showMatchStats = true;
    };
  }

  async spawnWord() {
    if (this.wordList.length < 15) {
      this.loadWords();
    }
    const wordText = this.wordList[Math.floor(Math.random() * this.wordList.length)];
    this.wordList = this.wordList.filter((word) => word !== wordText);
    const x = Math.random() * 80 + 10;

    const newWord: WordType = {
      id: this.nextId++,
      word: wordText,
      x: x,
      y: 0,
      speed: Math.random() * 1 + this.speedFactor,
    };
    this.speedFactor += 0.1;

    this.ngZone.run(() => {
      this.words.push(newWord);
    });
  }

  isLetterMatched(word: string, index: number): boolean {
    const input = this.inputText.trim().toLowerCase();
    const target = word.toLowerCase();
    return input.length > 0 && target.startsWith(input) && index < input.length;
  }

  updatePositions() {
    const footerHeight = 160;
    const headerHeight = 80;
    const screenHeight = window.innerHeight;
    const limit = screenHeight - footerHeight - 20;

    let lostHeart = false;

    this.ngZone.run(() => {
      for (let i = this.words.length - 1; i >= 0; i--) {
        this.words[i].y += this.words[i].speed;
        if (this.words[i].y > limit) {
          this.words.splice(i, 1);
          this.hearts = Math.max(0, this.hearts - 1);
          lostHeart = true;
          if (this.hearts > 0) {
            this.playErrorSound();
          }
        }

        if (this.words[i].word.toLowerCase() === this.inputText.trim().toLowerCase()) {
          this.points += this.words[i].word.length;
          this.words.splice(i, 1);
          this.wordsCount++;
          this.inputText = '';
        }
      }

      if (lostHeart && this.hearts === 0) {
        this.playGameOverSound();
        this.matchEnded = true;
        this.stopGame();
      }
    });
  }

  playErrorSound() {
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.type = 'sawtooth';
    oscillator.frequency.setValueAtTime(150, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(50, audioContext.currentTime + 0.3);

    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.start();
    oscillator.stop(audioContext.currentTime + 0.3);
  }

  playGameOverSound() {
    const audioContext = new (window.AudioContext || (window as any).webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.type = 'square';
    oscillator.frequency.setValueAtTime(200, audioContext.currentTime);
    oscillator.frequency.exponentialRampToValueAtTime(50, audioContext.currentTime + 1.0);

    gainNode.gain.setValueAtTime(0.15, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 1.0);

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.start();
    oscillator.stop(audioContext.currentTime + 1.0);
  }

  async loadWords() {
    for (const language of this.league?.languages || []) {
      const words = await this.wordsController.getRandomWords(language);
      this.wordList.push(...words);
    }
  }

  playAgain() {
    this.matchEnded = false;
    this.points = 0;
    this.wordsCount = 0;
    this.hearts = 3;
    this.words = [];
    this.speedFactor = 1;
    this.inputText = '';
    this.showMatchStats = false;
    this.startGame();
    setTimeout(() => {
      this.gameInput.nativeElement.focus();
    });
  }
}
