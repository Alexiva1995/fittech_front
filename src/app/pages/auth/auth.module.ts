import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AuthRoutingModule } from './auth-routing.module';
import { LoginComponent } from './login/login.component';
import { AuthComponent } from './auth.component';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { SignInComponent } from './sign-in/sign-in.component';
import { ModalEmailPageModule } from 'src/app/modal-email/modal-email.module';
import { ModalEmailPage } from 'src/app/modal-email/modal-email.page';
import { TermsComponent } from './terms/terms.component';
import { GenderComponent } from './gender/gender.component';
import { GoalsComponent } from './goals/goals.component';
import { TrainingLocationComponent } from './training-location/training-location.component';
import { InitialStepComponent } from './initial-step/initial-step.component';
import { ComponentsModule } from 'src/app/components/components.module';
import { HeartBeatComponent } from './heart-beat/heart-beat.component';
import { MeasurementsComponent } from './components/measurements/measurements.component';
import { MedicalHistoryComponent } from './components/medical-history/medical-history.component';
import { FamilyHistoryComponent } from './components/family-history/family-history.component';
import { HeartBeatModalComponent } from './components/heart-beat-modal/heart-beat-modal.component';


@NgModule({
  declarations: [
    AuthComponent,
    LoginComponent,
    SignInComponent,
    TermsComponent,
    GenderComponent,
    GoalsComponent,
    TrainingLocationComponent,
    InitialStepComponent,
    HeartBeatComponent,
    MeasurementsComponent,
    MedicalHistoryComponent,
    FamilyHistoryComponent,
    HeartBeatModalComponent
  ],
  imports: [
    CommonModule,
    AuthRoutingModule,
    FormsModule,
    IonicModule,
    ModalEmailPageModule,
    ComponentsModule
  ],
  entryComponents:[
    ModalEmailPage,
    HeartBeatModalComponent
  ]
})
export class AuthModule { }
