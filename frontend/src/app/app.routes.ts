import { Routes } from '@angular/router';
import { Login } from '../pages/login/login'
import { SignUp } from '../pages/sign-up/sign-up'


export const routes: Routes = [
    { path: '', redirectTo: 'login', pathMatch: 'full' },
    { path: 'login', component: Login },
    { path: 'sign-up', component: SignUp },

];
