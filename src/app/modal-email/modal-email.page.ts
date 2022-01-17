import { Component, OnInit } from '@angular/core';
import { UsuarioService } from 'src/app/services/usuario.service';
import { ApiFitechService } from '../services/api-fitech.service';
import { ModalController } from '@ionic/angular';
import { MensajesService } from '../services/mensajes.service';

@Component({
  selector: 'app-modal-email',
  templateUrl: './modal-email.page.html',
  styleUrls: ['./modal-email.page.scss'],
})
export class ModalEmailPage implements OnInit {
  registrar = {
    nombre:"",
    email:"",
    contra:"",
    reemail:"",
    recontra:""
  }
  constructor(
    private usuarioService:UsuarioService,
    public modalController: ModalController,
    private apiService:ApiFitechService,  
    private mensajeservice:MensajesService) { }

  ngOnInit() {
  }

  public error = {
    name: "",
    email: "",
    confirmEmail: "",
    pass: "",
    confirmPass: ""
  }

  private valid: boolean = false;

  public sending: boolean = false;

  async acceder(){

    this.valid = true;
    this.sending = true;

    this.checkName();
    this.checkEmail();
    this.checkConfirmEmail();
    this.checkPassword();
    this.checkConfirmPassword();

    if (this.valid) {
      await this.Email();
      this.Email2();

      if (this.checkErrors()) {
        this.usuarioService.registrarEmail(this.registrar)
        this.modalController.dismiss({
          salir:true
        });
      }

    }

    this.sending = false;

  }

  checkName(){
    if (this.registrar.nombre !=null && this.registrar.nombre.length > 2) {
      this.error.name = ""
    } else {
      this.valid = false;
      this.error.name = "Por favor introduzca un nombre valido"
    }
  }

  checkEmail(){
    if (this.registrar.email !=null && this.registrar.email.length > 2) {
      this.error.email = ""
    } else {
      this.valid = false;
      this.error.email = "Por favor introduzca un correo valido"
    }
    if (this.registrar.reemail != "") {
      this.checkConfirmEmail()
    }
  }

  checkConfirmEmail(){
    if (this.registrar.email == this.registrar.reemail) {
      this.error.confirmEmail = ""
    } else {
      this.valid = false;
      this.error.confirmEmail = "Los correos no coinciden"
    }
  }

  checkPassword(){
    if (this.registrar.contra !=null && this.registrar.contra.length > 2) {
      this.error.pass = ""
    } else {
      this.valid = false;
      this.error.pass = "Por favor introduzca una contraseña valida"
    }
    if (this.registrar.recontra != "") {
      this.checkConfirmPassword();
    }
  }

  checkConfirmPassword(){
    if(this.registrar.contra === this.registrar.recontra ){
      this.error.confirmPass = ""
    }else{
      this.valid = false;
      this.error.confirmPass = "Las contraseñas no coinciden"
    }
  }

  checkErrors(): boolean{
    return (
      (this.error.name == "" && 
      this.error.email == "" && 
      this.error.confirmEmail == "" &&
      this.error.pass == "" &&
      this.error.confirmPass == "")
    )
  }

  empty(): boolean{
    return (
      (this.registrar.nombre == "" && 
      this.registrar.email == "" && 
      this.registrar.reemail == "" &&
      this.registrar.contra == "" &&
      this.error.confirmPass == "")
    )
  }

  async Email(){

    const valido = await this.apiService.validarEmail(this.registrar.email)
    if(valido){
      this.error.email = "El correo ya está en uso"
      return
      // this.mensajeservice.alertaInformatica('el correo ya existe en nuestra base de datos')
      // this.registrar.email = null
    }else{
      return
    }

  }

  Email2(){
    if(this.registrar.email === this.registrar.reemail && this.registrar.email.length > 2){
      let valor = this.validateEmail(this.registrar.email)
        if(valor){
        }else{
        this.error.email = "Formato de correo invalido"
      }
    }else{
      this.error.confirmEmail = "Los correos no coinciden"
    }

  }

  
  //funcion para validar desde el js vainilla
  validateEmail(email) 
  {
      var re = /\S+@\S+\.\S+/;
      return re.test(email);
  }
  

  atras(){
    this.modalController.dismiss({
      salir:false
    });
  }
  


}
