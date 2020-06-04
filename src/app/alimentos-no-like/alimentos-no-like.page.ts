import { Component, OnInit } from '@angular/core';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { NavController, LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-alimentos-no-like',
  templateUrl: './alimentos-no-like.page.html',
  styleUrls: ['./alimentos-no-like.page.scss'],
})
export class AlimentosNoLikePage implements OnInit {
  alimentos: unknown;
  // este tipo de Datos no permite valores duplicado / es un array de tipo set
  grupoAlimentos = new Set()
  //array comun
  foods:any = []
  constructor(private service: NutricionService,
              private ruta: NavController,
              public loadingController: LoadingController,
              private utilities: MensajesService) { }

   ngOnInit() {
    this.getFoods()
  }

  async getFoods(){
    const valor = await this.service.getFoods()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        this.alimentos = valor
        console.log("valor",valor)
      }
  }

  agrupar(status:boolean,id:any){
    // logica
     this.grupoAlimentos.add(id)
  }
  
  async finalizar(){
    // destructuracion del array de set a un array normal
    this.foods = [...this.grupoAlimentos]
    console.log("array normal",this.foods)
    // esperar
    this.presentLoading()
    const validar = await this.service.foodNoDeseados(this.foods)
    this.loadingController.dismiss()
       if(validar){
          // esperar
          this.presentLoading2()
         const validar2 = await this.service.calculate_menu()
         this.loadingController.dismiss()
           if(validar2){
              this.ruta.navigateForward(['/indicadores'])
           }else{
            this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
           }
       }else{
        this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
       }
  }

  atras(){
    this.ruta.pop();
  }

  ucFirst(str) {
    /*   str = str.replace(/ /g, "."); */
         return str.substring(0, 1).toUpperCase() + str.substring(1); 
     }

     async presentLoading() {
      const loading = await this.loadingController.create({
        message: 'Por favor espere...',
      });
      await loading.present();
    }

    async presentLoading2() {
      const loading = await this.loadingController.create({
        message: 'Estamos calculando su menú',
      });
      await loading.present();
    }

}
