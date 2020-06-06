import {
  Component,
  OnInit
} from '@angular/core';
import {
  NavController, AlertController
} from '@ionic/angular';
import {
  Validators,
  FormBuilder,
  FormGroup,
  FormControl
} from '@angular/forms';
import { UsuarioService } from '../services/usuario.service';
import { MensajesService } from '../services/mensajes.service';
import { Camera, CameraOptions } from '@ionic-native/camera/ngx';
import {WebView} from '@ionic-native/ionic-webview/ngx';
@Component({
  selector: 'app-medidas',
  templateUrl: './medidas.page.html',
  styleUrls: ['./medidas.page.scss'],
})
export class MedidasPage implements OnInit {

  form: FormGroup;
  form_: FormGroup;
  imgSelected2: any;
  imgSelected: any;
  imgUri: any;
  constructor(private ruta: NavController, private fb: FormBuilder, private service: UsuarioService, private utilities: MensajesService, private camera: Camera, private webView: WebView, private alertCtrl: AlertController) {
    this.form = this.fb.group({
      min_waist:[0, Validators.min(10)],
      max_waist:[0,
        Validators.min(10)],
      hip:[0,
        Validators.min(10)],
      neck:[0,
        Validators.min(10)],
      right_thigh:[0,
        Validators.min(10)],
      left_thigh:[0,
        Validators.min(10)],
      right_arm:[0,
        Validators.min(10)],
      left_arm:[0,
        Validators.min(10)],
      right_calf:[0,
        Validators.min(10)],
      left_calf:[0,
        Validators.min(10)],
      torax:[0,
        Validators.min(10)],
      waist_hip:[0],
      profile_photo:[''],
      back_photo:[''],
      min_waist_:['Cm', Validators.required],
      max_waist_:['Cm',
        Validators.required],
      hip_:['Cm',
        Validators.required],
      neck_:['Cm',
        Validators.required],
      right_thigh_:['Cm',
        Validators.required],
      left_thigh_:['Cm',
        Validators.required],
      right_arm_:['Cm',
        Validators.required],
      left_arm_:['Cm',
        Validators.required],
      right_calf_:['Cm',
        Validators.required],
      left_calf_:['Cm',
        Validators.required],
      torax_:['Cm',
        Validators.required],
      waist_hip_:['Cm',
        Validators.required],
        profile_photo_:[''],
        back_photo_:[''],
    });
  
  }

  ngOnInit() {}

  goTo(url: string) {
    this.ruta.navigateForward(url);
  }

  atras() {
    this.ruta.pop();
  }

  change(controller: string){
    console.log(this.form.controls[controller+'_'].value);
    if(this.form.controls[controller+'_'].value === 'Pulgadas'){
      this.form.controls[controller].setValue(Math.round(this.form.controls[controller].value/2.54));
      console.log(this.form.controls[controller+'_'].value);
      
    }else{
      this.form.controls[controller].setValue(Math.round(this.form.controls[controller].value*2.54));

    }
    //this.convertToCm();
  }

  convertToCm(){
    let objects = Object.keys(this.form.value);
    objects.forEach((elemento, indice, array) =>{
      if(indice < 14){   
        let element = String(elemento+'_');
        console.log(this.form.controls[elemento+'_'].value);
        if(this.form.controls[element].value == 'Pulgadas'){
          this.form.controls[elemento].setValue(Math.round(this.form.controls[elemento].value*2.54));
        }
      }
  });
    console.log(this.form.value);
  }

  async measurement_record(){
    this.convertToCm();
    await this.service.measurement_record(this.form.value).then((res)=>{
      this.form.reset();
      this.goTo('/lineanutricional')
    },
    (err)=>{
      this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
    })
  }

  async captureImage(index) {
    let st = this.camera.PictureSourceType.CAMERA;
    await this.seleccionarFuente().then((result: boolean) => {
      if (result) {
        st = this.camera.PictureSourceType.CAMERA;
      } else {
        st = this.camera.PictureSourceType.PHOTOLIBRARY;
      }
    });

    const options: CameraOptions = {
      quality: 100,
      destinationType: this.camera.DestinationType.DATA_URL,
      mediaType: this.camera.MediaType.PICTURE,
      encodingType: this.camera.EncodingType.PNG,
      sourceType: st,
      allowEdit: true,
      targetHeight: 1080,
      targetWidth: 1080
    }

    this.camera.getPicture(options).then((imageData) => {

      if(index == 1){//frente
        this.imgSelected = this.webView.convertFileSrc(imageData);
        this.form.controls.back_photo.setValue('data:image/jpeg;base64'+imageData)
        this.imgUri = imageData;
      }
      if(index == 2){//perfil
        this.imgSelected2 = this.webView.convertFileSrc(imageData);
        this.form.controls.profile_photo.setValue('data:image/jpeg;base64'+imageData)
        this.imgUri = imageData;
      }
      // this.form.controls['fotoPerfil'].setValue(imageData);
     }, (err) => {
      // Handle error
      console.log("cameraE", err);
     });
  }

  seleccionarFuente() {
    return new Promise(async resolve => {

      const alert = await this.alertCtrl.create({
        header: 'Seleccionar Imágen',
        message: '¿Qué desea hacer?',
        buttons: [
          {
            text: "Tomar Foto",
            handler: () => {
              resolve(true);
            }
          },
          {
            text: "Buscar en Galería",
            handler: () => {
              resolve(false);
            }
          }
        ]
      });

      await alert.present();
    });
  }

  get forms(){
    return this.form;
  }

}