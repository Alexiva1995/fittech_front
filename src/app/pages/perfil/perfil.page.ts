import { Component, OnInit } from '@angular/core';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { NavController, AlertController } from '@ionic/angular';
import { FormBuilder, Validators, FormGroup } from '@angular/forms';
import { MensajesService } from 'src/app/services/mensajes.service';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.page.html',
  styleUrls: ['./perfil.page.scss'],
})
export class PerfilPage implements OnInit {
  comparar:any = 'perfil'
  image:any;
  genero:any
  form: FormGroup;
  constructor(private fb: FormBuilder, private apiService:ApiFitechService,
            private ruta:NavController,public alertController: AlertController,
            private service: ApiFitechService,private utilities: MensajesService,){ 
      this.form = this.fb.group({
        nombre:[null, Validators.required],
        email:[null,Validators.compose([Validators.required, Validators.pattern('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$')])],
        pass:['123456',Validators.compose([Validators.required, Validators.minLength(5)])],
        imagen:['*',Validators.required]
      });
  }
  

  ngOnInit() {
    this.getData()
  }

  segmentChanged(valor){
   this.comparar = valor.target.value
  }

  
  desconectar(){
    // LLAMO ALA RUTA PARA DESCONECTAR Y LO FUERZO A REDIRECIONAR AL LOGIN
    this.apiService.desconectarUsuario()
    this.ruta.navigateRoot(["/"])
  }


  
  async getData(){
    const valor = await this.service.obtenerUsuario()
      if(valor == false ){
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }else{
          if(valor['user'].gender == '1'){
            this.genero = "Hombre"
          }else{
            this.genero = "Mujer"
          }
         this.form.controls.nombre.setValue(valor['user'].name)
         this.form.controls.email.setValue(valor['user'].email)
      }
  }


  changeData(){
    console.log(this.form.controls.pass.value)
  }



}
