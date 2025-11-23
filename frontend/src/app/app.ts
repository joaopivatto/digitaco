import { Component, inject, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ToastModule } from 'primeng/toast';
import { LoadingService } from '../services/loading.service';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, ToastModule, CommonModule],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  protected readonly title = signal('digitaco');
  public loading$ = inject(LoadingService).loading$;
}
