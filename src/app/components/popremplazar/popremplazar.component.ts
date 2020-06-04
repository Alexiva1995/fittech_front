import { Component, OnInit } from '@angular/core';
import { PopoverController, NavController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-popremplazar',
  templateUrl: './popremplazar.component.html',
  styleUrls: ['./popremplazar.component.scss'],
})
export class PopremplazarComponent implements OnInit {

  constructor(public popoverController: PopoverController,private ApiService:ApiFitechService,private ruta:NavController) { }

  ngOnInit() {}

  async remplazar(){
    this.popoverController.dismiss()

    // AQUI REMPLAZA EL EJERCICIO SELECIONADO / por lo momento desabilite el anterior de gym
    // const valor = await this.ApiService.cambiarListadoEjercicio()




    const valor = await this.ApiService.cambiarListadoEjercicioHome()
      if(valor){
        // ANTERIOR
        // await this.ApiService.obtenerRutina() 
        // reset rutina
        this.ApiService.rutina = []
        console.log(this.ApiService.rutina)
        const validar = await this.ApiService.obtenerRutinaHome() 
         if(validar){
           this.ruta.navigateRoot(["/entrenamientos"])
         }
      }else{
        console.log("error en la conexion")
      }


  }

}
