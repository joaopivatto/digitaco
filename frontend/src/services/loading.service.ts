import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class LoadingService {
  private activeCount = 0;
  private subject = new BehaviorSubject<boolean>(false);
  public readonly loading$ = this.subject.asObservable();

  start() {
    this.activeCount++;
    if (!this.subject.getValue()) this.subject.next(true);
  }

  stop() {
    if (this.activeCount > 0) this.activeCount--;
    if (this.activeCount === 0 && this.subject.getValue()) this.subject.next(false);
  }

  reset() {
    this.activeCount = 0;
    this.subject.next(false);
  }
}