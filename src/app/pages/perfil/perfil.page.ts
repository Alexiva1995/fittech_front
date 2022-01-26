import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { NavController, AlertController, IonSlides } from '@ionic/angular';
import { MUser } from 'src/app/models/user/user.model';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.page.html',
  styleUrls: ['./perfil.page.scss'],
})
export class PerfilPage implements OnInit {

  constructor( 
    private apiService:ApiFitechService,
    private ruta:NavController,
    private alert: AlertController
  ) { }

  public user: MUser = null;
  public slideIndex: number = 0;
  public password: string = "";
  public confirmPassword: string = "";
  public newPassword: string = "";

  ngOnInit() {
    this.user = this.apiService.getUserData();
    console.log(this.user);
  }

  public async logOut(){
    // LLAMO ALA RUTA PARA DESCONECTAR Y LO FUERZO A REDIRECIONAR AL LOGIN
    const a = await this.alert.create({
      // header: 'Cerrar sesión',
      subHeader: `¿Esta segur${this.user.gender ? 'o' : 'a'} que desea cerrar su sesión?`,
      buttons: [{
        text: 'Aceptar',
        role: 'accept'
      }, {
        text: 'Cancelar',
        role: 'cancel'
      }]
    })

    await a.present()

    const { role } = await a.onDidDismiss();

    if (role == 'accept') {
      this.apiService.desconectarUsuario()
      this.ruta.navigateRoot(["/"])
    }
  }

  public changeSlide(slides: IonSlides){
    slides.getActiveIndex().then((res) => {
      this.slideIndex = res;
    })
  }


}
