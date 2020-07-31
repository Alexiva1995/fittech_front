import { Component, OnInit } from '@angular/core';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { MensajesService } from 'src/app/services/mensajes.service';
import { LoadingController } from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';

@Component({
  selector: 'app-medidas',
  templateUrl: './medidas.component.html',
  styleUrls: ['./medidas.component.scss'],
})
export class MedidasComponent implements OnInit {

  constructor(private service: ApiFitechService, private utilities: MensajesService,
              private usuarioService: UsuarioService,
              public loadingController: LoadingController,) { }
  
  medidasUser:any = {
    // peso:null,
    // altura:null,
    min_waist:null,
    max_waist:null,
    hip:null,
    neck:null,
    right_thigh:null,
    left_thigh:null,
    right_arm:null,
    left_arm:null,
    right_calf:null,
    left_calf:null,
    torax:null,
    profile_photo:null,
    front_photo:null,
    back_photo:null,
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
        console.log(valor['measurement_record'])
        //  this.medidasUser.altura = valor['user'].stature
        //  this.medidasUser.peso = valor['user'].weight
         this.medidasUser.min_waist = valor['measurement_record'].min_waist
         this.medidasUser.max_waist = valor['measurement_record'].max_waist
         this.medidasUser.hip = valor['measurement_record'].hip
         this.medidasUser.neck = valor['measurement_record'].neck
         this.medidasUser.right_thigh = valor['measurement_record'].right_thigh
         this.medidasUser.left_thigh = valor['measurement_record'].left_thigh
         this.medidasUser.right_arm = valor['measurement_record'].right_arm
         this.medidasUser.left_arm = valor['measurement_record'].left_arm
         this.medidasUser.right_calf = valor['measurement_record'].right_calf
         this.medidasUser.left_calf = valor['measurement_record'].left_calf
         this.medidasUser.torax = valor['measurement_record'].torax
         this.medidasUser.profile_photo = valor['measurement_record'].profile_photo
         this.medidasUser.front_photo = valor['measurement_record'].front_photo
         this.medidasUser.back_photo = valor['measurement_record'].back_photo

      }
  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }

  async update(){
    this.presentLoading()
    const data = await this.usuarioService.measurement_record(this.medidasUser)
    this.loadingController.dismiss()
    console.log(data)
    if(data){
      this.utilities.notificacionUsuario('Medidas actualizado' , "dark")
    }else{
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
    }
  }

}
