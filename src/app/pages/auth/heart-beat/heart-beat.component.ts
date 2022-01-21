import { Component, OnInit } from '@angular/core';
import { ModalController, LoadingController } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { UsuarioService } from 'src/app/services/usuario.service';
import { HeartBeatModalComponent } from '../components/heart-beat-modal/heart-beat-modal.component';


@Component({
  selector: 'app-corazon',
  templateUrl: './heart-beat.component.html',
  styleUrls: ['./heart-beat.component.scss'],
})
export class HeartBeatComponent implements OnInit {

  constructor(public modalController: ModalController,private ruta:NavController,
    private ApiService:ApiFitechService,private UsuarioService:UsuarioService,
    public loadingController: LoadingController) { }
    habilitar = true

  ngOnInit() {
  }

  async abrirmodal(valor){
    if(valor == 0) {
      const modal = await this.modalController.create({
        component: HeartBeatModalComponent,
        componentProps:{
          dato:'cuello'
        }
      });
       await modal.present();
      const {data} = await modal.onDidDismiss()

      if(data.salir){
        // LLAMAR ESPERA
        this.presentLoading()
        // Registro
        const validoRegistro = await this.ApiService.Registrar(this.UsuarioService.datosPersonales)
          if(validoRegistro){
            // Antecedente
            const validoAntecedente = await this.ApiService.Antecedentefamiliar(this.UsuarioService.condicionPersona)
              if(validoAntecedente){
              // Corazon ULTIMO PASO
              const valido = await this.ApiService.Latidos(this.UsuarioService.condicionPersona.latidos)
                if(valido){
                  // Termina espera
                  this.loadingController.dismiss()
                  this.ruta.navigateRoot(['/auth/heart-beat-results'])
                }
              }
          }

      }else{
        return
      }
    }

    if(valor == 1) {
      const modal = await this.modalController.create({
        component: HeartBeatModalComponent,
        componentProps:{
          dato:'pulso'
        }
      });
       await modal.present();
      const {data} = await modal.onDidDismiss()
      if(data.salir){
      // LLAMAR ESPERA
      this.presentLoading()
      // Registro
      const validoRegistro = await this.ApiService.Registrar(this.UsuarioService.datosPersonales)
        if(validoRegistro){
          // Antecedente
          const validoAntecedente = await this.ApiService.Antecedentefamiliar(this.UsuarioService.condicionPersona)
            if(validoAntecedente){
            // Corazon ULTIMO PASO
            const valido = await this.ApiService.Latidos(this.UsuarioService.condicionPersona.latidos)
              if(valido){
                // Termina espera 
                this.loadingController.dismiss()
                this.ruta.navigateRoot(['/mensajecorazon'])
              }
            }
        }
      }else{
        return
      }
    }

  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }



}
