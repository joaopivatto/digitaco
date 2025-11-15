import { Component, Input } from '@angular/core';
import { NgClass } from '@angular/common';

@Component({
  selector: 'app-card',
  standalone: true,
  imports: [NgClass],
  templateUrl: './card.html',
  styleUrl: './card.scss',
})
export class Card {
  @Input() variant: '1' | '2' | '3' | '4' = '1';
  @Input() icon = 'pi-play';
  @Input() title = 'PLAY';
  @Input() description = 'Jogue agora mesmo!';

}
