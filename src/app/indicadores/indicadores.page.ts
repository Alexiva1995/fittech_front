import { Component, OnInit } from '@angular/core';
import { NavController, LoadingController } from '@ionic/angular';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';

@Component({
  selector: 'app-indicadores',
  templateUrl: './indicadores.page.html',
  styleUrls: ['./indicadores.page.scss'],
})
export class IndicadoresPage implements OnInit {
    dato:any
  constructor(private ruta: NavController,
              private service: NutricionService,
              public loadingController: LoadingController,
              private utilities: MensajesService) { }

  ngOnInit() {
    this.getIndicators()
  }

  async getIndicators(){
    this.presentLoading()
    const valor = await this.service.indicadores()
    this.loadingController.dismiss()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        this.dato = valor
        console.log("que recibo" , valor)
      }
  }

  atras(){
    this.ruta.pop();
  }

  goTo(){
    this.ruta.navigateForward(['/bateria-alimento'])
  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }




}
