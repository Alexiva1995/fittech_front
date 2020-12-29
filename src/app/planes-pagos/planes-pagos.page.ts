import { Component, OnInit } from '@angular/core';
import { LoadingController, NavController } from '@ionic/angular';
import { MensajesService } from '../services/mensajes.service';
import { NutricionService } from '../services/nutricion.service';
import es from '@angular/common/locales/es';
import { registerLocaleData } from '@angular/common';

@Component({
  selector: 'app-planes-pagos',
  templateUrl: './planes-pagos.page.html',
  styleUrls: ['./planes-pagos.page.scss'],
})
export class PlanesPagosPage implements OnInit {
  dato:any

  constructor(private service: NutricionService,
    private utilities: MensajesService,
    private ruta:NavController,
    public loadingController: LoadingController,) { }

  ngOnInit() {
    registerLocaleData( es );
    this.getPremium()
  }

  async getPremium(){
    this.presentLoading()
    const valor = await this.service.GetPrice()
    this.loadingController.dismiss()
      if(valor == false ){
        this.utilities.notificacionUsuario("Ocurrio un error, revise su conexión","danger")
      }else{
        this.dato = valor
      }
  }


  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
      cssClass: 'my-loading',
    });
    await loading.present();
  }

  pago(id){
    switch (id) {
    case 1:
      this.ruta.navigateRoot(['/'])
    break;
    
    case 2:
      console.log("pagar2")

    break;
    
    case 3:
      console.log("pagar3")

    break;
    
    case 4:
      console.log("pagar4")

    break;
    
    case 5:
      console.log("pagar5")

    break;
    
    default:
      break;
    }

  }


}
