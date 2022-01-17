import { Component, OnInit, Input } from '@angular/core';
import { ModalController} from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';
import { MensajesService } from '../services/mensajes.service';

@Component({
  selector: 'app-modal-corazon-info',
  templateUrl: './modal-corazon-info.page.html',
  styleUrls: ['./modal-corazon-info.page.scss'],
})
export class ModalCorazonInfoPage implements OnInit {
  @Input() dato
  mostrar:boolean = false
  heart_rate:number
  escapar:boolean = false

  public timesCompleted: number = 0;
  public frecuency: number = 0;
  public frecuencyConfirm: number = 0;

  constructor(public modalController: ModalController , private usuarioservicio:UsuarioService,
              private mensajeservice:MensajesService) { }

  ngOnInit() {
    if(this.dato == 'cuello'){
      this.mostrar = true
    }else{
      this.mostrar = false
    }
  }

  valor(valor){
    if(valor.target.value > 1 && valor.target.value <= 25  ) {
      this.heart_rate = valor.target.value * 6;
      this.escapar = true
    }else{
      this.escapar = false
    }
    
  }

  salir(){
    if(this.escapar && this.heart_rate > 10 && this.heart_rate  <= 100){
      this.usuarioservicio.latidos(this.heart_rate)
      console.log(this.usuarioservicio.condicionPersona)
      this.modalController.dismiss({
        salir:true
      });
    }else{
      this.mensajeservice.alertaInformatica('Por favor introduzca un valor valido')
    }
  }

  public onBlur(value: number){

    if (value == 0) {
      if(this.frecuency > 1 && this.frecuency <= 25  ) {
        // this.heart_rate = this.frecuency * 6;
        // this.escapar = true
      }else{
        this.mensajeservice.alertaInformatica('Por favor introduzca un valor valido')
        this.frecuency = 0;
      }
      
    } else {
      if(this.frecuencyConfirm > 1 && this.frecuencyConfirm <= 25  ) {
        // this.heart_rate = this.frecuencyConfirm * 6;
        // this.escapar = true
      }else{
        this.mensajeservice.alertaInformatica('Por favor introduzca un valor valido')
        this.frecuencyConfirm = 0;
      }
    }

  }

  public proceed(){
    this.heart_rate = (this.frecuency + this.frecuencyConfirm) / 2 * 6;
    if(this.heart_rate > 10 && this.heart_rate  <= 100){
      this.usuarioservicio.latidos(this.heart_rate)
      this.modalController.dismiss({
        salir:true
      });
    } else {
      this.mensajeservice.alertaInformatica('Por favor introduzca un valor valido')
    }
  }

  atras(){
    this.modalController.dismiss({
      salir:false
    });
  }

  public completed(){
    this.timesCompleted++
  }

}