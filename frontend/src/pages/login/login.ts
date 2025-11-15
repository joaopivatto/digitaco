import { Component, inject } from '@angular/core';
import { InputComponent } from '../../components/input/input';
import { Button } from '../../components/button/button';
import { RouterLink } from '@angular/router';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [InputComponent, FormsModule, Button, RouterLink, ReactiveFormsModule],
  templateUrl: './login.html',
  styleUrl: './login.scss',
})
export class Login {
  label: string = 'Texto';

  private formBuilder = inject(FormBuilder);
  protected loginForm = this.formBuilder.group({
    email: [null, [Validators.required, Validators.email]],
    password: [null, Validators.required]
  });

  onSubmit() {
    console.log(this.loginForm);
  }
}
