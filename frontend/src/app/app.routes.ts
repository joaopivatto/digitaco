import { Routes } from '@angular/router';
import { Login } from '../pages/login/login';
import { SignUp } from '../pages/sign-up/sign-up';
import { Home } from '../pages/home/home';
import { Game } from '../pages/game/game';
import { GlobalRanking } from '../pages/global-ranking/global-ranking';
import { Leagues } from '../pages/leagues/leagues';
import { League } from '../pages/leagues/league/league';

import { History } from '../pages/history/history';
import { UserSettings } from '../pages/user-settings/user-settings';
import { NewLeague } from '../pages/leagues/new-league/new-league';

export const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: Login },
  { path: 'sign-up', component: SignUp },
  { path: 'home', component: Home },
  { path: 'game', component: Game },
  { path: 'game/:leagueId', component: Game },
  { path: 'global-ranking', component: GlobalRanking },
  { path: 'history', component: History },
  { path: 'user-settings', component: UserSettings },
  { path: 'leagues', component: Leagues },
  { path: 'league/:id', component: League },
  { path: 'new-league', component: NewLeague },
];
