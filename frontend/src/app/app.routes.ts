import { Routes } from '@angular/router';
import { Login } from '../pages/login/login'
import { SignUp } from '../pages/sign-up/sign-up'
import { Home } from '../pages/home/home'
import { Game } from '../pages/game/game'
import { GlobalRanking } from '../pages/global-ranking/global-ranking'
import { Leagues } from '../pages/leagues/leagues'
import { History } from '../pages/history/history'
import { UserSettings } from '../pages/user-settings/user-settings'


export const routes: Routes = [
    { path: '', redirectTo: 'login', pathMatch: 'full' },
    { path: 'login', component: Login },
    { path: 'sign-up', component: SignUp },
    { path: 'home', component: Home },
    { path: 'game', component: Game },
    { path: 'global-ranking', component: GlobalRanking },
    { path: 'leagues', component: Leagues },
    { path: 'history', component: History },
    { path: 'user-settings', component: UserSettings },

];
