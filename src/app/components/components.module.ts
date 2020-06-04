import { NgModule} from '@angular/core';
import { IonicModule } from '@ionic/angular'; 

import { CommonModule } from '@angular/common';
import {EdadComponent} from './edad/edad.component'
import {EstaturaComponent} from './estatura/estatura.component'
import {PesoComponent} from './peso/peso.component'
import {HistorialComponent} from './historial/historial.component'
import { ModalInfoPage } from '../modal-info/modal-info.page';
import { ModalInfoPageModule } from '../modal-info/modal-info.module';
import {PopinfoComponent} from './popinfo/popinfo.component'
import { PopremplazarComponent } from './popremplazar/popremplazar.component';
import { PopmensajeComponent } from './popmensaje/popmensaje.component';
import { FormsModule } from '@angular/forms';
import { AntecedentefmliarComponent } from '../antecedentefmliar/antecedentefmliar.component';
import { BackBtnComponent } from '../back-btn/back-btn.component';


@NgModule({
  entryComponents:[
   ModalInfoPage
  ],
  declarations: [
    EdadComponent,
    EstaturaComponent,
    PesoComponent,
    HistorialComponent,
    PopinfoComponent,
    PopremplazarComponent,
    PopmensajeComponent,
    AntecedentefmliarComponent,
    BackBtnComponent
  ],
  exports:[
    EdadComponent,
    EstaturaComponent,
    PesoComponent,
    HistorialComponent,
    PopinfoComponent,
    PopremplazarComponent,
    PopmensajeComponent,
    AntecedentefmliarComponent,
    BackBtnComponent
  ],
  imports: [
    FormsModule,
    CommonModule,
    IonicModule,
    ModalInfoPageModule,
  ]
})
export class ComponentsModule { }
