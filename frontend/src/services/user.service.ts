import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { User } from '../entities/user';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private userSource = new BehaviorSubject<User | null>(null);

  public currentUser = this.userSource.asObservable();

  constructor() {
    const raw = localStorage.getItem('user');
    if (raw) {
      try {
        const parsed = JSON.parse(raw) as User;
        this.userSource.next(parsed);
      } catch {
        localStorage.removeItem('user');
        this.userSource.next(null);
      }
    }

    window.addEventListener('storage', (e) => {
      if (e.key === 'user') {
        if (e.newValue) {
          try {
            this.userSource.next(JSON.parse(e.newValue) as User);
          } catch {
            this.userSource.next(null);
          }
        } else {
          this.userSource.next(null);
        }
      }
    });
  }

  public setUsuario(usuario: User | null) {
    this.userSource.next(usuario);
    if (usuario) {
      localStorage.setItem('user', JSON.stringify(usuario));
    } else {
      localStorage.removeItem('user');
    }
  }

  public clearUsuario() {
    this.userSource.next(null);
    localStorage.removeItem('user');
  }

  public getUsuario(): User | null {
    return this.userSource.getValue();
  }
}
