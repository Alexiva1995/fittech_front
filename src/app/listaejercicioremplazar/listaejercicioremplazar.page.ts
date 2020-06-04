import { Component, OnInit } from '@angular/core';
import { PopremplazarComponent } from '../components/popremplazar/popremplazar.component';
import { PopoverController } from '@ionic/angular';
import { ApiFitechService } from '../services/api-fitech.service';

@Component({
  selector: 'app-listaejercicioremplazar',
  templateUrl: './listaejercicioremplazar.page.html',
  styleUrls: ['./listaejercicioremplazar.page.scss'],
})
export class ListaejercicioremplazarPage implements OnInit {
  serie = {}
  constructor( public popoverController: PopoverController,private ApiService:ApiFitechService) { }

  ngOnInit() {
    this.serie =  this.ApiService.rutinaListadoRemplazar
    console.log(this.serie)
  }

  async mostrarpop(evento,id){
 
    const popover = await this.popoverController.create({
      component:PopremplazarComponent,
      showBackdrop:false,
      event:evento,
    });
      console.log("ID DEL NUEVO EJERCICIO en el listado",id)
      this.ApiService.asignarNuevoEjercicio(id)
    await popover.present()
  }


}
