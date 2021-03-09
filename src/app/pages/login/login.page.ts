import { Component, OnInit } from "@angular/core";
import { ApiFitechService } from "src/app/services/api-fitech.service";
import { NavController } from "@ionic/angular";
import { AlertController } from "@ionic/angular";
import { MensajesService } from "src/app/services/mensajes.service";
import * as moment from "moment";
//google
import * as firebase from "firebase/app";
import { AngularFireAuth } from "@angular/fire/auth";
import { GooglePlus } from "@ionic-native/google-plus/ngx";
import { Platform } from "@ionic/angular";
import { Observable } from "rxjs";
//facebook
import { Facebook, FacebookLoginResponse } from "@ionic-native/facebook/ngx";
import { Storage } from "@ionic/storage";

@Component({
  selector: "app-login",
  templateUrl: "./login.page.html",
  styleUrls: ["./login.page.scss"],
})
export class LoginPage implements OnInit {
  public logeado: Observable<firebase.User>;
  googleData = null;
  facebookData = null;

  login = {
    email: null,
    password: null,
    pushToken: null,
    pushIdToken: null,
  };

  constructor(
    private ApiService: ApiFitechService,
    private ruta: NavController,
    private mensajeservice: MensajesService,
    public afAuth: AngularFireAuth,
    private gplus: GooglePlus,
    public fb: Facebook,
    public alertController: AlertController,
    private platform: Platform,
    private storage: Storage

  ) {
    this.logeado = this.afAuth.authState;
  }

  ngOnInit() {}

  ionViewWillEnter() {

    // llamado cache
    this.ApiService.cargarFittechApp().then(resp=>{
      console.log("si no tiene nada , llamas" ,resp)
      if(resp === false){
         this.ApiService.guardarFittechApp()
      }
    })

  }

  acceder() {
    if (this.login.password.length > 1 && this.login.email.length > 1) {
      this.dashboard(this.login);
    } else {
      return;
    }
  }

  googleLogin() {
    console.log("hola")
  }



  facebookLogin() {
    this.fb
      .login(["public_profile", "email"])
      .then((res: FacebookLoginResponse) => {
        this.fb
          .api(
            "me?fields=id,name,email,first_name,picture.width(720).height(720).as(picture)",
            []
          )
          .then((profile) => {
            this.facebookData = { email: profile["email"] };
            this.dashboard(this.facebookData);
          });
      });
  }

  async dashboard(valor) {
    this.login.pushToken = localStorage.getItem("pushToken");
    this.login.pushIdToken = localStorage.getItem("pushIdToken");
    const valido = await this.ApiService.Login(valor);
    if (valido) {
      this.ruta.navigateRoot(["/tabs"]);
    } else {
      this.mensajeservice.alertaInformatica(
        "Correo o contraseña no son correctas"
      );
    }
  }

  goto(url: string) {
    this.ruta.navigateForward([url]);
  }

  async recuperar() {
    const alert = await this.alertController.create({
      cssClass: "my-custom-class",
      header: "Por favor, introduzca su correo electrónico",
      inputs: [
        {
          name: "email",
          type: "text",
          placeholder: "email",
        },
      ],
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
          cssClass: "secondary",
          handler: () => {
            console.log("ignorar");
          },
        },
        {
          text: "Ok",
          handler: async (data) => {
            const validar = await this.ApiService.recuperarPassword(data);
            if (validar) {
              this.mensajeservice.alertaInformatica(
                "Su clave fue enviada a su correo electrónico"
              );
            } else {
              this.mensajeservice.alertaInformatica(
                "El correo electrónico no existe en nuestra base de datos"
              );
            }
          },
        },
      ],
    });

    await alert.present();
  }
}
