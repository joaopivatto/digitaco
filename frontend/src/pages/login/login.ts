import { Component } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';

import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [InputComponent, FormsModule, Button],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  label: string = 'Texto';

  loginData = {
    email: '',
    senha: ''
  };

  onSubmit() {
    console.log(this.loginData);
  }
}
