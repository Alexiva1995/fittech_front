import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PerfilPageRoutingModule } from './perfil-routing.module';

import { PerfilPage } from './perfil.page';
import { ComponentsModule } from 'src/app/components/components.module';
import { IMCPopoverComponent } from './components/imc-popover/imc-popover.component';
import { MeasureComponent } from './components/measure/measure.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PerfilPageRoutingModule,
    ComponentsModule
  ],
  declarations: [
    PerfilPage,
    IMCPopoverComponent,
    MeasureComponent
  ],
  entryComponents: [
    IMCPopoverComponent
  ]
})
export class PerfilPageModule {}
