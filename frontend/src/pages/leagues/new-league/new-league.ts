import { Component, inject } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ButtonModule } from 'primeng/button';
import { MessageService } from 'primeng/api';
import { LeaguesController } from '../../../controllers/leagues';
import { InputComponent } from '../../../components/input/input';
import { RouterModule, Router } from '@angular/router';
import { ToastModule } from 'primeng/toast';
import { ListaIdiomas, IIdiomaDetalhe } from '../../../entities/languages';
import { MultiSelectModule } from 'primeng/multiselect';

@Component({
  selector: 'app-new-league',
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    ButtonModule,
    InputComponent,
    RouterModule,
    ToastModule,
    MultiSelectModule,

  ],
  templateUrl: './new-league.html',
  styleUrl: './new-league.scss',
})
export class NewLeague {
  router = inject(Router);
  constructor(
    private leaguesController: LeaguesController,
    private messageService: MessageService
  ) {}
  private formBuilder = inject(FormBuilder);
  newLeagueForm = this.formBuilder.group({
    name: ['', Validators.required],
    password: ['', [Validators.required, Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)]],
    languages: [[], Validators.required],
  });
  languagesList = ListaIdiomas;

  async onSubmit() {
    if (this.newLeagueForm.valid) {
      const name = this.newLeagueForm.value.name as string;
      const password = this.newLeagueForm.value.password as string;
      const languages = (this.newLeagueForm.value.languages || []).map((language: IIdiomaDetalhe) => language.id);
      try {
        await this.leaguesController.create({
          name,
          password,
          languages,
        });
        this.messageService.add({
          severity: 'success',
          summary: 'Sucesso',
          detail: 'Liga criada com sucesso',
        })
      } catch (error) {
        console.error('Erro ao criar liga', error);
        this.messageService.add({
          severity: 'error',
          summary: 'Erro',
          detail: 'Erro ao criar liga',
        });
      }
      this.newLeagueForm.reset();
    }
  }
}
