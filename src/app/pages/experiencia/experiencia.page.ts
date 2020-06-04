import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/services/usuario.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-experiencia',
  templateUrl: './experiencia.page.html',
  styleUrls: ['./experiencia.page.scss'],
})
export class ExperienciaPage implements OnInit {

  constructor(private usuarioservicio:UsuarioService,private ruta:NavController) { }

  ngOnInit() {
  }

  experiencia(valor){
    this.usuarioservicio.experiencia(valor)
    this.ruta.navigateRoot(['/lugar-ejercicios'])
  }

  atras(){
    this.ruta.navigateRoot(['/objetivo'])
  }


}
