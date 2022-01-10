import { Component, OnInit } from '@angular/core';
import { NavController, AlertController} from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';


@Component({
  selector: 'app-objetivo',
  templateUrl: './objetivo.page.html',
  styleUrls: ['./objetivo.page.scss'],
})
export class ObjetivoPage implements OnInit {

  constructor(private ruta:NavController , private usuarioservicio:UsuarioService, private alertController: AlertController) { }
  info: boolean;
  title: string;
  mesaj: string;

  ngOnInit() {
  }

  objetivo(valor){
    this.usuarioservicio.objetivo(valor)
    this.ruta.navigateForward(['/lugar-ejercicios'])
  }

  login(){
    this.ruta.navigateForward(['/login'])
  }

  mostrar(valor){
    this.info = !valor;
}
//Mostrar info alert
async openInfo(value) {
  if(value == 0){
    this.title = 'Estar en forma';
    this.mesaj = 'Quiero mantenerme en forma, y hacer un trabajo balanceado entre quema de grasa y ganancia muscular';
  }else if(value == 1){
    this.title = 'Ganar musculatura';
    this.mesaj = 'Soy delgado y mi enfoque principal es ganar masa muscular magra ';
  }else if(value == 2){
    this.title = 'Adelgazar';
    this.mesaj = 'Estoy en sobrepeso, quiero adelgazar y crear a su vez una base muscular ';
  }
    const alert = await this.alertController.create({
      cssClass: 'center',
      header: this.title,
      message: this.mesaj,
      buttons: ['OK']
    });
    await alert.present();

  }

cerrar(valor){
  if(valor == 1){
    this.open_forma = false;
  }else{
    this.info = false;

  }
}

}
