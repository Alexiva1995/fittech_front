import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/services/usuario.service';
import { NavController } from '@ionic/angular';


@Component({
  selector: 'app-training-location',
  templateUrl: './training-location.component.html',
  styleUrls: ['./training-location.component.scss'],
})
export class TrainingLocationComponent implements OnInit {

  constructor(private usuarioservicio:UsuarioService,private ruta:NavController) { }

  ngOnInit() {
  }

  entrenar(valor){
    this.usuarioservicio.entrenar(valor)
    this.ruta.navigateForward(['/auth/initial-step'])
  }

  atras(){
    this.ruta.pop()
  }

}
