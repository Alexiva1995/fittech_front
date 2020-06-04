import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';

@Component({
  selector: 'app-alimentos-seleccion',
  templateUrl: './alimentos-seleccion.page.html',
  styleUrls: ['./alimentos-seleccion.page.scss'],
})
export class AlimentosSeleccionPage implements OnInit {
  dataRecibida:any

  constructor(private capturar:ActivatedRoute,
              private service: NutricionService,
              private utilities: MensajesService) { }

  ngOnInit() {
    //  parametros del id
    this.dataRecibida = this.capturar.snapshot.paramMap.get('id')
    switch (this.dataRecibida) {
      case 'Desayuno':
        this.getFoods(0)
        break
      case 'Almuerzo':
        this.getFoods(2)
        break
      case  'Snack':
        this.getFoods(1)
        break
      default:
        this.getFoods(3)
        break
    }
  }

  async getFoods(comida:any){
    const valor = await this.service.menu(comida)
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        console.log("que recibo" , valor)
      }
  }

}
