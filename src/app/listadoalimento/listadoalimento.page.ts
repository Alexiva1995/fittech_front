import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-listadoalimento',
  templateUrl: './listadoalimento.page.html',
  styleUrls: ['./listadoalimento.page.scss'],
})
export class ListadoalimentoPage implements OnInit {
  dataRecibida:any
  alimentos:any
  datosUsuario:any = [];
  foods: string;
  carbo: any = 0;
  grasa: any = 0;
  protein: any = 0;
  id:any
  typefood:any
  mostraragregar:boolean = false

  constructor(private capturar:ActivatedRoute,
    private service: NutricionService,
    private utilities: MensajesService,
    public alertController: AlertController,
    private ruta: NavController) { }

ngOnInit() {
//  parametros del id
this.dataRecibida = this.capturar.snapshot.paramMap.get('id');
switch (this.dataRecibida) {
  case 'Desayuno':
    this.typefood = 0
  this.getFoods(0)
  this.foods = './assets/img/desayuno-grande.jpg'
  break
  case 'Almuerzo':
    this.typefood = 2
  this.getFoods(2)
  this.foods = './assets/img/almuerzo-grande.jpg'
  break
  case  'Snack':
  this.getFoods(1)
  this.typefood = 1
  this.foods = './assets/img/snack-grande.jpg'
  break
  default:
  this.getFoods(3)
  this.typefood = 3
  this.foods = './assets/img/cena-grande.jpg'
  break
  }
}
async getFoods(comida:any){
  let today = new Date().toJSON().slice(0,10).replace(/-/g,'/');
    console.log(today)
  const data = await this.service.ListadoComida(comida,today)
      if(data == false){
        this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        this.alimentos = data['menu'].menu_food
        this.id = data['menu'].id
        if(this.alimentos.length == 0){
           this.mostraragregar = true
        }
      }
}

  ucFirst(str) {
  /*   str = str.replace(/ /g, "."); */
  return str.substring(0, 1).toUpperCase() + str.substring(1); 
  }


 
  B_todo() {
    this.alimentos = []
  }

  // mensaje de reanudar
   borrar(food:any) {     
    console.log("eliminar  un registro" ,food)
    let alimentos2 = this.alimentos.filter(item => item.food_id !== food)
    this.alimentos = alimentos2
  }

  guardarMenu(){

    if(this.alimentos.length == 0){
      // borrar todo 
      this.service.BorrarMenu(this.id).then((res) => {
        console.log(res);
        this.utilities.alertaInformatica(this.dataRecibida+ ' Actualizado');
         this.ruta.navigateRoot('/bateria-alimento')
      }).catch((err) => {
       this.utilities.alertaInformatica('Error al guardar '+ this.dataRecibida)
      });
   }else{
      let menu = {
        "menu_id" : this.id,
        "type_food": this.typefood,
        "total_proteins": 0,
        "total_greases": 0,
        "total_carbos": 0,
        "total_calories": 0,
        "foods": []
      }

      this.alimentos.forEach(element => {
          menu.total_calories += element.food.calories;
          menu.total_proteins += element.food.protein;
          menu.total_greases += element.food.greases;
          menu.total_carbos += element.food.carbo;
          let food = [ element.food.id, parseInt(element.quantity)]
          menu.foods.push(food);
      });
          
      // Actualizar 
      this.service.ActualizarComida(menu).then((res) => {
        console.log(res);
        this.utilities.alertaInformatica(this.dataRecibida+ ' Actualizado');
         this.ruta.navigateRoot('/bateria-alimento')
      }).catch((err) => {
       this.utilities.alertaInformatica('Error al guardar '+ this.dataRecibida)
      });

   }


  }
  

  agregar(){
    this.ruta.navigateForward([`/alimentos-editar/${this.dataRecibida}`])
  }


}
