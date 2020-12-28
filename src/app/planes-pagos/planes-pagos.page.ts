import { Component, OnInit } from '@angular/core';
import { LoadingController } from '@ionic/angular';
import { MensajesService } from '../services/mensajes.service';
import { NutricionService } from '../services/nutricion.service';

@Component({
  selector: 'app-planes-pagos',
  templateUrl: './planes-pagos.page.html',
  styleUrls: ['./planes-pagos.page.scss'],
})
export class PlanesPagosPage implements OnInit {
  dato:any

  constructor(private service: NutricionService,
    private utilities: MensajesService,
    public loadingController: LoadingController,) { }

  ngOnInit() {
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
    });
    await loading.present();
  }

}
