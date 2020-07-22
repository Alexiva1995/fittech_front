import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-alimentos-seleccion',
  templateUrl: './alimentos-seleccion.page.html',
  styleUrls: ['./alimentos-seleccion.page.scss'],
})
export class AlimentosSeleccionPage implements OnInit {
  dataRecibida:any
  alimentos:any = [];
  alimentosAyer:any = []
  datosUsuario:any = [];
  foods: string;
  carbo:any = 0;
  protein:any = 0;
  grasa:any = 0;
  typefoods:number = 1
  totalCarbo: any;
  totalgrease: any;
  totalprotein: any;
  measurement: string = 'gr';
  today:any
  constructor(private capturar:ActivatedRoute,
              private service: NutricionService,
              private utilities: MensajesService,
              public alertController: AlertController,
              private navCtrl: NavController) { }

  ngOnInit() {
    //  parametros del id
    this.dataRecibida = this.capturar.snapshot.paramMap.get('id');
    switch (this.dataRecibida) {
      case 'Desayuno':
        this.getFoods(0)
        this.comprobarMenu(0)
        this.foods = './assets/img/desayuno-grande.jpg'
        break
      case 'Almuerzo':
        this.getFoods(2)
        this.comprobarMenu(2)

        this.foods = './assets/img/almuerzo-grande.jpg'
        break
      case  'Snack':
        this.getFoods(1)
        this.comprobarMenu(1)

        this.foods = './assets/img/snack-grande.jpg'
        break
      default:
        this.getFoods(3)
        this.comprobarMenu(3)
        this.foods = './assets/img/cena-grande.jpg'
        break
    }
  }

  async getFoods(comida:any){
    this.today = new Date().toJSON().slice(0,10).replace(/-/g,'/');
    console.log("fecha de hoy" , this.today)

    const valor = await this.service.menu(comida);
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
        console.log(valor)
        this.alimentos = valor['Foods']
        this.alimentos.forEach(element => {
          element['cantidad'] = null;
          if(element.measure == null){
            element['measurement'] =  'unidad';
          }else{
            element['measurement'] =  'gr';
          }
        });
        this.datosUsuario = valor['Menu'];
        this.totalCarbo = this.datosUsuario.carbo;
        this.totalgrease = this.datosUsuario.grease;
        this.totalprotein = this.datosUsuario.protein;

      }
  }

  ucFirst(str) {
    /*   str = str.replace(/ /g, "."); */
         return str.substring(0, 1).toUpperCase() + str.substring(1); 
     }

   

      calculateStats(){
        this.carbo = 0;
        this.grasa = 0;
        this.protein = 0;

        
          this.alimentos.forEach(element => {
            
            if(element.cantidad > 0){
              if(element.measurement === 'unidad'){
              console.log(element);
              console.log('medida casera')

              this.carbo += element.carbo*element.cantidad;
              this.grasa += element.greases*element.cantidad;
              this.protein += element.protein*element.cantidad;
            }else{
              this.carbo += this.convertion(1, element.carbo, element.cantidad)
              this.grasa += this.convertion(1, element.greases, element.cantidad)
              this.protein += this.convertion(1, element.protein, element.cantidad)
              console.log(element)
              console.log('Aplicar la regla de 3')

            }
          }
          });
    
      }

      convertion(a, b, c){
        //A es el valor unitario
        //B es el equivalente en grasa/proteina/carbo de ese valor unitario
        //C es la incognita a encontrar
        let x;
        x = b*c/a;
        console.log(x)
        return x;
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
          "day":this.today,
          "type_food": this.datosUsuario.type_food,
          "total_proteins": this.protein,
          "total_greases": this.grasa,
          "total_carbos": this.carbo,
          "total_calories": 0,
          "foods": []
        }

  

        this.alimentos.forEach(element => {
          if(element.cantidad > 0){
            menu.total_calories += element.calories;
            if(element.measurement == 'gr'){//Unidad en gramos, ml, kg etc.
            let food = [ element.id, element.cantidad, element.type_measure]
            menu.foods.push(food);
            }else{//Valor unitario casero.
            let food = [ element.id, element.cantidad]
            menu.foods.push(food);
            }
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



    selecionartarjeta(tipo){

      switch (tipo) {
        case 0:
          this.typefoods = 0
          break;

        case 1:
          this.typefoods = 1
         break;

        case 2:
          this.typefoods = 2
          break;
      
          default:
            break;
      }

    }

    calculador(){

    }

    change(index){
      this.alimentos[index].cantidad = 0;
    }


  
    async comprobarMenu(comida:any){
      let yesterday  = new Date(Date.now() - 86400000).toJSON().slice(0,10).replace(/-/g,'/');
      console.log("fecha de ayer" , yesterday)
      const data = await this.service.ListadoComida(comida,yesterday)
        if(data == false || data['menu'] == null ){
          return
        }
         this.alimentosAyer  = data
         this.alerta(comida)
    }


    // mensaje de reanudar
    async alerta(mensaje:any) {
      const alert = await this.alertController.create({
        header: `Deseas cargar el menú anterior`,
        cssClass: 'customMensaje1',
        buttons: [
          {
            text: 'Cancelar',
            role: 'cancel',
            cssClass: 'cancelButton',
            handler: (blah) => {
              return
            }
          }, {
            text: 'Confirmar',
            cssClass: 'confirmButton',
            handler: () => {
              // mensaje confirmacion
              this.MenuAnterior()
            }
          }
        ]
  
      });
  
      await alert.present();
    }

  
    // cargar menu anterior
    MenuAnterior(){
      let menu = {
        "day":this.today,
        "type_food": this.alimentosAyer.menu.type_food,
        "total_proteins": this.alimentosAyer.menu.total_proteins,
        "total_greases": this.alimentosAyer.menu.total_greases,
        "total_carbos": this.alimentosAyer.menu.total_carbos,
        "total_calories": this.alimentosAyer.menu.total_calories,
        "foods": []
      }

       this.alimentosAyer.menu.menu_food.forEach(element => {
        let food = [ element.food_id, parseInt(element.quantity) , 'gr']
        menu.foods.push(food);
       });  

       this.service.storeMenu(menu).then((res) => {
        console.log(res);
        this.utilities.alertaInformatica(this.dataRecibida+ ' Guardado');
         this.navCtrl.navigateRoot('/bateria-alimento')
      }).catch((err) => {
       this.utilities.alertaInformatica('Error al guardar '+ this.dataRecibida)
      });

    }




}
