import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { UsuarioService } from 'src/app/services/usuario.service';

@Component({
  selector: 'app-modal-info',
  templateUrl: './modal-info.page.html',
  styleUrls: ['./modal-info.page.scss'],
})
export class ModalInfoPage implements OnInit {
  datoEnfermedades = {
    patologiaCardiaca:null,
    enfermedadPulmonar:null,
    hipertension:0,
    hipotension:0,
    desvanecimiento:0,
    diabete:0,
    dolorPecho:0,
    fatiga:0,
    noEnfermedad:false
  }

  escapar:boolean = false

  constructor(public modalController: ModalController , private usuarioservicio:UsuarioService) { }
  ngOnInit() {
  }

  historialmedico(datos){
  
      if(datos == 'enfermedad'){
        if(this.datoEnfermedades.noEnfermedad){
          this.datoEnfermedades.patologiaCardiaca = null
          this.datoEnfermedades.enfermedadPulmonar = null
          this.datoEnfermedades.hipertension = 0
          this.datoEnfermedades.hipotension = 0
          this.datoEnfermedades.desvanecimiento = 0
          this.datoEnfermedades.diabete = 0
          this.datoEnfermedades.dolorPecho = 0
          this.datoEnfermedades.fatiga = 0
        }
        this.escapar = true
      }

      if(this.datoEnfermedades.patologiaCardiaca){
        this.datoEnfermedades.patologiaCardiaca = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true
      }else{
        this.datoEnfermedades.patologiaCardiaca = 0
      }

      if(this.datoEnfermedades.enfermedadPulmonar !=null){
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }

      if(this.datoEnfermedades.hipertension){
        this.datoEnfermedades.hipertension = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.hipertension = 0
      }

      if(this.datoEnfermedades.hipotension){
        this.datoEnfermedades.hipotension = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.hipotension = 0
      }

      if(this.datoEnfermedades.desvanecimiento){
        this.datoEnfermedades.desvanecimiento = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.desvanecimiento = 0
      }

      if(this.datoEnfermedades.diabete){
        this.datoEnfermedades.diabete = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.diabete = 0
      }

      if(this.datoEnfermedades.dolorPecho){
        this.datoEnfermedades.dolorPecho = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.dolorPecho = 0
      }

      if(this.datoEnfermedades.fatiga){
        this.datoEnfermedades.fatiga = 1
        this.datoEnfermedades.noEnfermedad = false
        this.escapar = true

      }else{
        this.datoEnfermedades.fatiga = 0
      }

  }

  salir(){

    if(this.escapar){
      this.usuarioservicio.enfermedades(this.datoEnfermedades)
      console.log(this.usuarioservicio.datosPersonales)
      this.modalController.dismiss({
        salir:true
      });
    }else{
      this.modalController.dismiss()
    }

  }

  avanzar(){

    if(this.escapar){
      this.usuarioservicio.enfermedades(this.datoEnfermedades)
      console.log(this.usuarioservicio.datosPersonales)
      this.modalController.dismiss({
        salir:true
      });
    }else{
      return
    }

  }

}
