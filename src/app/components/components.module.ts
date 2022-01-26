import { NgModule} from '@angular/core';
import { IonicModule } from '@ionic/angular'; 

import { CommonModule } from '@angular/common';
import {EdadComponent} from './edad/edad.component'
import {PesoComponent} from './peso/peso.component'
import { ModalInfoPage } from '../modal-info/modal-info.page';
import { ModalInfoPageModule } from '../modal-info/modal-info.module';
import {PopinfoComponent} from './popinfo/popinfo.component'
import { PopremplazarComponent } from './popremplazar/popremplazar.component';
import { PopmensajeComponent } from './popmensaje/popmensaje.component';
import { FormsModule } from '@angular/forms';
import { BackBtnComponent } from '../back-btn/back-btn.component';
import { StopwatchComponent } from './stopwatch/stopwatch.component';
import { BaseHeaderComponent } from './base-header/base-header.component';


@NgModule({
  entryComponents:[
   ModalInfoPage
  ],
  declarations: [
    EdadComponent,
    PesoComponent,
    PopinfoComponent,
    PopremplazarComponent,
    PopmensajeComponent,
    BackBtnComponent,
    StopwatchComponent,
    BaseHeaderComponent
  ],
  exports:[
    EdadComponent,
    PesoComponent,
    PopinfoComponent,
    PopremplazarComponent,
    PopmensajeComponent,
    BackBtnComponent,
    StopwatchComponent,
    BaseHeaderComponent
  ],
  imports: [
    FormsModule,
    CommonModule,
    IonicModule,
    ModalInfoPageModule,
  ]
})
export class ComponentsModule { }
