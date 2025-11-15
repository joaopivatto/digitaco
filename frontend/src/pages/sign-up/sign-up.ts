import { Component } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-sign-up',
  standalone: true,
  imports: [InputComponent, Button, FormsModule],
  templateUrl: './sign-up.html',
  styleUrl: './sign-up.scss',
})
export class SignUp {

  signUpData = {
    email: '',
    password: '',
    confirmPassword: ''
  };


  onSubmit() {
    console.log(this.signUpData);
    if (this.signUpData.password !== this.signUpData.confirmPassword) {
      alert('As senhas não coincidem');
      return;
    }
  }
}
