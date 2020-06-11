import { Component, OnInit } from '@angular/core';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { MensajesService } from 'src/app/services/mensajes.service';
import { LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-medidas',
  templateUrl: './medidas.component.html',
  styleUrls: ['./medidas.component.scss'],
})
export class MedidasComponent implements OnInit {

  constructor(private service: ApiFitechService, private utilities: MensajesService,
              public loadingController: LoadingController,) { }

  medidasUser:any = {
    peso:null,
    altura:null,
    cintura_min:null,
    cintura_max:null,
    cadera:null,
    cuello:null,
    muslo_d:null,
    muslo_i:null,
    brazo_d:null,
    brazo_i:null,
    pantorrilla_d:null,
    pantorrilla_i:null,
    torax:null,
  }

  ngOnInit() {
    this.getData()
  }

  async getData(){
    this.presentLoading()
    const valor = await this.service.obtenerUsuario()
    this.loadingController.dismiss()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
         this.medidasUser.altura = valor['user'].stature
         this.medidasUser.peso = valor['user'].weight
         this.medidasUser.cintura_min = valor['measurement_record'].min_waist
         this.medidasUser.cintura_max = valor['measurement_record'].max_waist
         this.medidasUser.cadera = valor['measurement_record'].hip
         this.medidasUser.cuello = valor['measurement_record'].neck
         this.medidasUser.muslo_d = valor['measurement_record'].right_thigh
         this.medidasUser.muslo_i = valor['measurement_record'].left_thigh
         this.medidasUser.brazo_d = valor['measurement_record'].right_arm
         this.medidasUser.brazo_i = valor['measurement_record'].left_arm
         this.medidasUser.pantorrilla_d = valor['measurement_record'].right_calf
         this.medidasUser.pantorrilla_i = valor['measurement_record'].left_calf
         this.medidasUser.torax = valor['measurement_record'].torax
      }
  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }

}
