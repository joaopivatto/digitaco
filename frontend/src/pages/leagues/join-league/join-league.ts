import { Component, EventEmitter, inject, Input, Output, SimpleChanges } from '@angular/core';
import { League } from '../../../entities/league';
import { LeaguesController } from '../../../controllers/leagues';
import { UsersController } from '../../../controllers/users';
import { MessageService } from 'primeng/api';
import { FormBuilder, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ButtonModule } from 'primeng/button';
import { InputComponent } from '../../../components/input/input'
import { LoadingService } from '../../../services/loading.service';


@Component({
  selector: 'app-join-league',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule, ButtonModule, InputComponent],
  templateUrl: './join-league.html',
  styleUrl: './join-league.scss',
})
export class JoinLeague {
  constructor(private leaguesController: LeaguesController, private usersController: UsersController, private messageService: MessageService, private loading: LoadingService) {}
  @Input() leagueId: number | null = null;
  league: League | null = null;

  private formBuilder = inject(FormBuilder);
  joinLeagueForm = this.formBuilder.group({
    password: [
      '',
      [
        Validators.required,
        Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/),
      ],
    ],
  });

  ngOnChanges(changes: SimpleChanges) {
    if (changes['leagueId']) {
      this.leagueId = changes['leagueId'].currentValue;
      if (this.leagueId) {
        this.leaguesController.findById(this.leagueId).then((league) => {
          this.league = league;
        });
      }
    }
  }

  @Output() joined = new EventEmitter<void>();

  async joinLeague() {
    if (this.joinLeagueForm.valid) {
      try {
        this.loading.start();
        await this.usersController.joinLeague({ password: this.joinLeagueForm.value.password! }, this.leagueId!);
        this.messageService.add({
          severity: 'success',
          summary: 'Success',
          detail: 'Você foi adicionado à liga com sucesso!',
        });
        this.joined.emit();
        this.joinLeagueForm.reset();
      } catch (error: any) {
        this.messageService.add({
          severity: 'error',
          summary: 'Error',
          detail: error.message,
        });
      } finally {
        this.loading.stop();
      }
    }
  }
}
