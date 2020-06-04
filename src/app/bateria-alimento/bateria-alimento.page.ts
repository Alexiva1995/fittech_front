import { Component, OnInit } from '@angular/core';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-bateria-alimento',
  templateUrl: './bateria-alimento.page.html',
  styleUrls: ['./bateria-alimento.page.scss'],
})
export class BateriaAlimentoPage implements OnInit {
  alimentos:any;
  constructor(private service: NutricionService,
              private ruta: NavController,
              private utilities: MensajesService) { }

  ngOnInit() {
    this.getResume()
  }

  
  async getResume(){
    const valor = await this.service.getResumes()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        this.alimentos = valor
        console.log("que recibo" , valor)
      }
  }

  seleccion(url:string){
    this.ruta.navigateForward([`/alimentos-seleccion/${url}`])
  }

}
