import { Component, OnInit } from '@angular/core';
import { NavController} from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';


@Component({
  selector: 'app-objetivo',
  templateUrl: './goals.component.html',
  styleUrls: ['./goals.component.scss'],
})
export class GoalsComponent implements OnInit {

  constructor(private ruta:NavController , private usuarioservicio:UsuarioService) { }
  info: boolean;

  ngOnInit() {
  }

  objetivo(valor){
    this.usuarioservicio.objetivo(valor)
    this.ruta.navigateForward(['/auth/training-location'])
  }

  mostrar(valor){
    this.info = !valor;
  }

  cerrar(valor){
    this.info = false;

  }

}
