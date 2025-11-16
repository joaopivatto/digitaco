import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { User } from '../entities/user';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private userSource = new BehaviorSubject<User | null>(null);

  public currentUser = this.userSource.asObservable();

  constructor() { }

  public setUsuario(usuario: User | null) {
    this.userSource.next(usuario);
  }
}
