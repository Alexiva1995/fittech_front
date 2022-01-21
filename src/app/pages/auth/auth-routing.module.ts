import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthComponent } from './auth.component';
import { GenderComponent } from './gender/gender.component';
import { GoalsComponent } from './goals/goals.component';
import { InitialStepComponent } from './initial-step/initial-step.component';
import { LoginComponent } from './login/login.component';
import { SignInComponent } from './sign-in/sign-in.component';
import { TermsComponent } from './terms/terms.component';
import { TrainingLocationComponent } from './training-location/training-location.component';
import { HeartBeatComponent } from './heart-beat/heart-beat.component';
import { HeartBeatResultsComponent } from './heart-beat-results/heart-beat-results.component';


const routes: Routes = [
  {
    path: '',
    component: AuthComponent,
    children: [
      {
        path: 'login',
        component: LoginComponent
      },
      {
        path: 'sign-in',
        component: SignInComponent
      },
      {
        path: 'terms',
        component: TermsComponent
      },
      {
        path: 'gender',
        component: GenderComponent
      },
      {
        path: 'goals',
        component: GoalsComponent
      },
      {
        path: 'training-location',
        component: TrainingLocationComponent
      },
      {
        path: 'initial-step',
        component: InitialStepComponent
      },
      {
        path: 'heart-beat',
        component: HeartBeatComponent
      },
      {
        path: 'heart-beat-results',
        component: HeartBeatResultsComponent
      },
      {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full'
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthRoutingModule { }
