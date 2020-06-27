import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-alimentos-seleccion',
  templateUrl: './alimentos-seleccion.page.html',
  styleUrls: ['./alimentos-seleccion.page.scss'],
})
export class AlimentosSeleccionPage implements OnInit {
  dataRecibida:any
  alimentos:any
  datosUsuario:any = [];
  foods: string;
  carbo = 0;
  protein = 0;
  grasa = 0;
  totalCarbo: any;
  totalgrease: any;
  totalprotein: any;
  constructor(private capturar:ActivatedRoute,
              private service: NutricionService,
              private utilities: MensajesService,
              private navCtrl: NavController) { }

  ngOnInit() {
    //  parametros del id
    this.dataRecibida = this.capturar.snapshot.paramMap.get('id');
    switch (this.dataRecibida) {
      case 'Desayuno':
        this.getFoods(0)
        this.foods = './assets/img/desayuno-grande.jpg'
        break
      case 'Almuerzo':
        this.getFoods(2)
        this.foods = './assets/img/almuerzo-grande.jpg'
        break
      case  'Snack':
        this.getFoods(1)
        this.foods = './assets/img/snack-grande.jpg'
        break
      default:
        this.getFoods(3)
        this.foods = './assets/img/cena-grande.jpg'
        break
    }
  }

  async getFoods(comida:any){
    const valor = await this.service.menu(comida);
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        console.log(valor)
        this.alimentos = valor['Foods']
        this.alimentos.forEach(element => {
          element['cantidad'] = 0;
        });
        this.datosUsuario = valor['Menu'];
        this.totalCarbo = this.datosUsuario.carbo+5;
        this.totalgrease = this.datosUsuario.grease+5;
        this.totalprotein = this.datosUsuario.protein+5;

      }
  }

  ucFirst(str) {
    /*   str = str.replace(/ /g, "."); */
         return str.substring(0, 1).toUpperCase() + str.substring(1); 
     }

     add(index){
     this.alimentos[index].cantidad++;
     this.calculateStats();
     }  

     decrease(index){
      this.alimentos[index].cantidad--;
      this.calculateStats();
      }

      calculateStats(){
        this.carbo = 0;
        this.grasa = 0;
        this.protein = 0;
        this.alimentos.forEach(element => {
          if(element.cantidad > 0){
            this.carbo += element.carbo*element.cantidad;
            this.grasa += element.greases*element.cantidad;
            this.protein += element.protein*element.cantidad;
          }
        });
       
        this.carbo = Math.round(this.carbo*100)/100;
        this.grasa = Math.round(this.grasa*100)/100;
        this.protein = Math.round(this.protein*100)/100;
        console.log(this.totalCarbo);
        console.log(this.totalgrease);
        console.log(this.totalprotein);
        
      }

      progressBar(data, total){
        if((data*100/total)/100 >= 1){
          return 1;
        }else{
          return (data*100/total)/100;
        }
      }

      guardarMenu(){
        let menu = {
          "type_food": this.datosUsuario.type_food,
          "total_proteins": this.totalprotein,
          "total_greases": this.totalgrease,
          "total_carbos": this.totalCarbo,
          "total_calories": 0,
          "foods": []
        }

        this.alimentos.forEach(element => {
          if(element.cantidad > 0){
            menu.total_calories += element.calories;
            let food = [ element.id, element.cantidad]
            menu.foods.push(food);
          }
        });
        if (this.carbo > this.datosUsuario.carbo || this.grasa > this.datosUsuario.grease || this.protein > this.datosUsuario.protein) {
          this.utilities.alertaInformatica('Los alimentos seleccionados exceden los valores permitidos para esta comida')
        } else {
            // evitar guardar vacio
              if(!menu.foods.length){
                this.utilities.alertaInformatica('Debe seleccionar un alimento')
              }else{
                this.service.storeMenu(menu).then((res) => {
                  console.log(res);
                  this.utilities.alertaInformatica(this.dataRecibida+ ' Guardado');
                   this.navCtrl.navigateRoot('/bateria-alimento')
                }).catch((err) => {
                 this.utilities.alertaInformatica('Error al guardar '+ this.dataRecibida)
                });
              }
        }
          
      }
}
