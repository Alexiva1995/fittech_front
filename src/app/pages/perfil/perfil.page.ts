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
  formAvatar: FormGroup;
  constructor(private fb: FormBuilder, private apiService:ApiFitechService,
            private ruta:NavController,public alertController: AlertController,
            private service: ApiFitechService,private utilities: MensajesService,){ 
      this.formAvatar = this.fb.group({
        nombre:['', Validators.required],
         imagen:[''],
         genero:['']
      });
      this.form = this.fb.group({
        email:['', Validators.compose([Validators.required, Validators.pattern('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$')])],
        pass:['', Validators.compose([Validators.required, Validators.minLength(5)])],
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
      /*     if(valor['user'].gender == '1'){
            this.genero = "Hombre"
          }else{
            this.genero = "Mujer"
          } */
         this.formAvatar.controls.nombre.setValue(valor['user'].name);
         this.form.controls.email.setValue(valor['user'].email);
         this.formAvatar.controls.genero.setValue(valor['user'].gender);
         this.formAvatar.controls.imagen.setValue(valor['user'].imagen);
      }
  }


  changeData(){
    if(!this.form.pristine){//Editar correo y contrase;a
     //llamar metodo para editar correo
    }
    if(!this.formAvatar.pristine){//Editar avatar
     //llamar metodo para avatar
    }

  }



}
