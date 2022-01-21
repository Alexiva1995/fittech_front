import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/services/usuario.service';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { NavController, LoadingController } from '@ionic/angular';
import { MensajesService } from 'src/app/services/mensajes.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-terminos',
  templateUrl: './terms.component.html',
  styleUrls: ['./terms.component.scss'],
})
export class TermsComponent implements OnInit {


  datosCargados


  constructor(
    private usuarioService:UsuarioService , 
    private ApiService:ApiFitechService,
    private ruta: NavController,
    public loadingController: LoadingController,
    private mensajeservice:MensajesService,
    private router: Router
    ) {
    this.datosCargados = this.usuarioService.datosPersonales
   }

  ngOnInit() {
  }

  
  async registrar(){
    /*   this.presentLoading(); */
    /*   const valido = await this.ApiService.Registrar(this.usuarioService.datosPersonales)
      if(valido){ */
       /*  this.loadingController.dismiss() */
      this.router.navigate(['/auth/gender'])
        // this.ruta.navigateForward(['/home'])
     /*  }else{
        console.log("fail en el Registrado")
      } */
  }


  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }


}
  