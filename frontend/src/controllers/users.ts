import { Injectable } from '@angular/core';
import { Config } from '../config';
import {
  validateConfirmPassword,
  validateEmail,
  validateName,
  validatePassword,
} from '../utils/validations';
import { User } from '../entities/user';

@Injectable({ providedIn: 'root' })
export class UsersController {
  constructor(private config: Config) {}


  async joinLeague(leagueId: number): Promise<string> {
    const response = await fetch(`${this.config.API_BASE_URL}/users/league/${leagueId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.message;
  }

  async leaveLeague(leagueId: number): Promise<string> {
    const response = await fetch(`${this.config.API_BASE_URL}/users/league/${leagueId}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data.message;
  }

  async logOut(): Promise<boolean> {
    const response = await fetch(`${this.config.API_BASE_URL}/users/logout.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return true;
  }

  async changePassword(input: {
    email: string;
    password: string;
    confirmPassword: string;
  }): Promise<boolean> {
    this.validate(input, 'change-password');

    const response = await fetch(`${this.config.API_BASE_URL}/users/change-password.php`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(input),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return true;
  }

  async signIn(input: { email: string; password: string }): Promise<User> {
    this.validate(input, 'sign-in');

    const response = await fetch(`${this.config.API_BASE_URL}/users/sign-in.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(input),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return {
      name: data.name,
      email: data.email,
    };
  }

  async signUp(input: { name: string; email: string; password: string }): Promise<boolean> {
    debugger
    this.validate(input, 'sign-up');
    const response = await fetch(`${this.config.API_BASE_URL}/users/sign-up.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(input),
    });
    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return true;
  }

  private validate(payload: Record<string, any>, domain: string): void {
    if (domain === 'sign-up') {
      const nameIsValid = validateName(payload['name']);
      if (!nameIsValid) throw new Error('Nome inválido ou vazio.');
    }

    if (domain !== 'change-password') {
      const emailIsValid = validateEmail(payload['email']);
      if (!emailIsValid) throw new Error('Email inválido!');
    } else {
      const confirmPasswordIsValid = validateConfirmPassword(
        payload['password'],
        payload['confirmPassword']
      );
      if (!confirmPasswordIsValid) throw new Error('As senhas não coincidem!');
    }

    const passwordIsValid = validatePassword(payload['password']);
    if (!passwordIsValid) throw new Error('Senha inválida!');
  }
}
