import { NgModule } from '@angular/core';
import { CapitalizadoPipe } from './capitalizado.pipe';
import { CommonModule } from '@angular/common';


@NgModule({
  imports:[
    CommonModule,
  ],
  declarations: [
    CapitalizadoPipe
  ],
  exports:[
    CapitalizadoPipe
  ]
})
export class PipesModule {}
