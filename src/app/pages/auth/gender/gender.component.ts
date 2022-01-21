import { Component } from '@angular/core';
import { NavController } from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';



@Component({
  selector: 'app-gender',
  templateUrl: 'gender.component.html',
  styleUrls: ['gender.component.scss'],
})
export class GenderComponent {

  constructor(private ruta:NavController , private usuarioservicio:UsuarioService){}

  genero(valor){
    this.usuarioservicio.genero(valor)
    this.ruta.navigateForward(['/auth/goals'])
  }

  

}
