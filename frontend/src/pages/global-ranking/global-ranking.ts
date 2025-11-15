import { Component } from '@angular/core';
import { Header } from '../../components/header/header'

@Component({
  selector: 'app-global-ranking',
  standalone: true,
  imports: [Header],
  templateUrl: './global-ranking.html',
  styleUrl: './global-ranking.scss',
})
export class GlobalRanking {

}
