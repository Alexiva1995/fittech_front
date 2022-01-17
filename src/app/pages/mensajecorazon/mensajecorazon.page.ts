import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';

@Component({
  selector: 'app-mensajecorazon',
  templateUrl: './mensajecorazon.page.html',
  styleUrls: ['./mensajecorazon.page.scss'],
})
export class MensajecorazonPage implements OnInit {
  public titulo:any
  public mensaje:any

  public riskMessage: string = "";

  public userInfo: any;

  constructor(
    private ruta:NavController,
    private ApiService:ApiFitechService,
    public alertController: AlertController
  ) { }

  ngOnInit() {

    this.userInfo = this.ApiService.latidocorazon

    console.log(this.userInfo);

    if(this.userInfo.heart_rate === 6){
      this.titulo = "¡Felicidades!",
      this.mensaje ="Tú frecuencia cardiaca está entre las mejores de acuerdo a tu edad y género."
      // ¡Felicitaciones!
    }

    if(this.userInfo.heart_rate === 5){
      this.titulo = "Buena",
      this.mensaje ="Tú frecuencia cardiaca está entre las buenas de acuerdo a tu edad y género."
      // ¡Estupendo! 
    }

    if(this.userInfo.heart_rate === 4){
      this.titulo = "Por encima de la media",
      this.mensaje ="Tú frecuencia cardiaca está por encima del promedio de acuerdo a tu edad y género."
      // ¡Muy bien!, 
    }

    if(this.userInfo.heart_rate === 3){
      this.titulo = "Media",
      this.mensaje ="Tú frecuencia cardiaca está en promedio de acuerdo a tu edad y género."
      // ¡Muy bien!, 
    }

    if(this.userInfo.heart_rate === 2){
      this.titulo = "Por de bajo de la media",
      this.mensaje ="Tú frecuencia cardiaca está por debajo del promedio de acuerdo a tu edad y género."
      // ¡No hay problema!, Vamos a mejorar! 
    }

    if(this.userInfo.heart_rate === 1){
      this.titulo = "Mala",
      this.mensaje = "Tú frecuencia cardiaca es mala de acuerdo a tu edad y género."
      // Con esfuerzo todo se puede, ¡A trabajar!, 
    }

    if(this.userInfo.heart_rate === 0){
      this.titulo = "Muy mala",
      this.mensaje = "Te recomendamos ir al médico, tu frecuencia cardíaca es muy mala de acuerdo a tu edad y género."
    }

    switch (this.userInfo.risk) {
      case 2:
        this.riskMessage = "Usted posee un índice de riesgo alto"
        break;

      case 1:
        this.riskMessage = "Usted posee un índice de riesgo medio"
        break;
    
      default:
        this.riskMessage = "Usted posee un índice de riesgo bajo"
        break;
    }


  }

  siguiente(){
    // if(this.userInfo.heart_rate === 6){
    //   this.ruta.navigateRoot(['/tabs/dashboard'])
    // }

    // if(this.userInfo.heart_rate === 5){
    //   this.ruta.navigateRoot(['/tabs/dashboard'])
    // }

    // if(this.userInfo.heart_rate === 4){
    //   this.ruta.navigateRoot(['/tabs/dashboard'])
    // }

    // if(this.userInfo.heart_rate === 3){
    //   this.ruta.navigateRoot(['/tabs/dashboard'])
    // }

    // if(this.userInfo.heart_rate === 2){
    //   this.ruta.navigateRoot(['/tabs/dashboard'])
    // }

    // if(this.userInfo.heart_rate === 1){
    //   this.ApiService.desconectarUsuario()
    //   this.presentAlert()
    //   this.ruta.navigateRoot(['/'])
    // }

    // if(this.userInfo.heart_rate === 0){
    //   this.ApiService.desconectarUsuario()
    //   this.presentAlert()
    //   this.ruta.navigateRoot(['/'])
    // }

    if (this.userInfo.risk == 2) {
      this.presentAlert()
      this.ApiService.desconectarUsuario()
      this.ruta.navigateRoot(['/'])
    } else {
      this.ruta.navigateRoot(['/tabs/dashboard']);
    }

  }


    // mensaje del corazon

    async presentAlert() {
      const alert = await this.alertController.create({
        header: 'Fittech',
        cssClass: 'customMensaje',
        message: 'Lo sentimos, lo más que queremos es ayudarte, pero no estás apto para continuar según la información de salud que nos diste, te recomendamos ir al médico, y te esperamos de vuelta pronto.',
        buttons: [
          {
            text: 'Ok',
            cssClass: 'confirmButton'
          }
        ]
      });
  
      await alert.present();
    }

}
