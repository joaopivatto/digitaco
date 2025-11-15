import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { Card } from '../../components/card/card';
import { RouterLink } from "@angular/router";

@Component({
  selector: 'app-home',
  imports: [Header, Card, RouterLink],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {

}
