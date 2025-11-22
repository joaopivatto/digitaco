import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewLeague } from './new-league';

describe('NewLeague', () => {
  let component: NewLeague;
  let fixture: ComponentFixture<NewLeague>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NewLeague]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewLeague);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
