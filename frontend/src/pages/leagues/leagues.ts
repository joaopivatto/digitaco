import { Component } from '@angular/core';
import { Header } from '../../components/header/header'

@Component({
  selector: 'app-leagues',
  standalone: true,
  imports: [Header],
  templateUrl: './leagues.html',
  styleUrl: './leagues.scss',
})
export class Leagues {

}
