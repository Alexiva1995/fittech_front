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
    peso:'1',
    altura: '2',
    cintura_min:'3',
    cintura_max:'4',
    cadera:'5',
    cuello:'6',
    muslo_d: '7',
    muslo_i:'8',
    brazo_d:'9',
    brazo_i:'10',
    pantorrilla_d:'11',
    pantorrilla_i:'12',
    torax:'13',
  }

  ngOnInit() {
    // this.getData()
  }

  async getData(){
    this.presentLoading()
    const valor = await this.service.obtenerUsuario()
    this.loadingController.dismiss()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        console.log("que recibo" , valor)
      }
  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }

}
