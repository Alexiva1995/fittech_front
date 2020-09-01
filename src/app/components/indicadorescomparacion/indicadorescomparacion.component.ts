import { Component, OnInit } from '@angular/core';
import { NavController, AlertController, LoadingController } from '@ionic/angular';
import { NutricionService } from 'src/app/services/nutricion.service';
import { MensajesService } from 'src/app/services/mensajes.service';

@Component({
  selector: 'app-indicadorescomparacion',
  templateUrl: './indicadorescomparacion.component.html',
  styleUrls: ['./indicadorescomparacion.component.scss'],
})
export class IndicadorescomparacionComponent implements OnInit {
  fechas:any
  valor: any;
  valor2: any;

  indicadores_ante = {
    imc: null,
    ica:null,
    tmba:null,
    tmb:null,
    estrategia_nutricional:null,
  }
  indicadores_despues = {
    imc: null,
    ica:null,
    tmba:null,
    tmb:null,
    estrategia_nutricional:null,
  }

  constructor(private ruta: NavController,
    private service: NutricionService,
    public alertController: AlertController,
    public loadingController: LoadingController,
    private utilities: MensajesService) { }

  ngOnInit() {
    this.getResume()
  }


  async getResume(){
    this.presentLoading() 
     const valor:any = await this.service.historyIndicators()
     if(valor == false ){
      this.loadingController.dismiss() 
     this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
     }else{
      this.loadingController.dismiss() 
      this.fechas = valor
      console.log(valor)
     } 
 }

 async presentLoading() {
  const loading = await this.loadingController.create({
    message: 'Por favor espere...',
    cssClass: 'my-loading',
  });
  await loading.present();
}



desde(valor){
  console.log(valor.target.value)
  this.valor = valor.target.value;
  this.buscador(this.valor,"ante")
}

hasta(valor){
  console.log(valor.target.value)
  this.valor2 = valor.target.value;
  this.buscador(this.valor2,"despues")
}

buscador(valor, filtrar){
  const resultado = this.fechas.find( elemento => elemento.id === parseInt(valor) );
  console.log(resultado);
  if(filtrar === "ante"){
    this.indicadores_ante.imc = 0,
    this.indicadores_ante.ica = 0,
    this.indicadores_ante.tmba = resultado.tmba,
    this.indicadores_ante.tmb = resultado.tmb,
    this.indicadores_ante.estrategia_nutricional = resultado.strategy_n + '%'

  }else{
    this.indicadores_despues.imc = 0,
    this.indicadores_despues.ica = 0,
    this.indicadores_despues.tmba = resultado.tmba,
    this.indicadores_despues.tmb = resultado.tmb,
    this.indicadores_despues.estrategia_nutricional = resultado.strategy_n + '%'
  }

}


}
