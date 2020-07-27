import {
  Component,
  OnInit
} from '@angular/core';
import {
  NavController, AlertController, LoadingController, ModalController
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
import { ModalMedidasPage } from '../modal-medidas/modal-medidas.page';
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

  constructor(private ruta: NavController, private fb: FormBuilder, 
              private service: UsuarioService, private utilities: MensajesService, 
              public loadingController: LoadingController,private camera: Camera, 
              public modalController: ModalController,
              private webView: WebView, private alertCtrl: AlertController) {
    this.form = this.fb.group({
      min_waist:[null, Validators.required],
      max_waist:[null,Validators.required],
      hip:[null,Validators.required],
      neck:[null,Validators.required],
      right_thigh:[null,Validators.required],
      left_thigh:[null,Validators.required],
      right_arm:[null, Validators.required],
      left_arm:[null,Validators.required],
      right_calf:[null,Validators.required],
      left_calf:[null,Validators.required],
      torax:[null, Validators.required],
      waist_hip:[0],
      profile_photo:[null,Validators.required],
      back_photo:[null,Validators.required],
      min_waist_:['Cm', Validators.required],
      max_waist_:['Cm',Validators.required],
      hip_:['Cm',Validators.required],
      neck_:['Cm',Validators.required],
      right_thigh_:['Cm', Validators.required],
      left_thigh_:['Cm',Validators.required],
      right_arm_:['Cm', Validators.required],
      left_arm_:['Cm',Validators.required],
      right_calf_:['Cm', Validators.required],
      left_calf_:['Cm',Validators.required],
      torax_:['Cm',Validators.required],
      waist_hip_:['Cm',Validators.required],
        profile_photo_:[null],
        back_photo_:[null],
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
    this.presentLoading()
    this.convertToCm();
    const data = await this.service.measurement_record(this.form.value)
      if(data){
        this.loadingController.dismiss()
        // this.form.reset();
        this.goTo('/lineanutricional')
      }else{
        this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      }
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
      quality: 70,
      destinationType: this.camera.DestinationType.DATA_URL,
      mediaType: this.camera.MediaType.PICTURE,
      encodingType: this.camera.EncodingType.JPEG,
      sourceType: st,
      allowEdit: true,
    }

    this.camera.getPicture(options).then((imageData) => {

      if(index == 1){//frente
        this.imgSelected = this.webView.convertFileSrc(imageData);
        // 'data:image/jpeg;base64'
        this.form.controls.back_photo.setValue(imageData)
        this.imgUri = imageData;
        console.log("imagen" , imageData)
        console.log("image espalda",this.form)

      }
      if(index == 2){//perfil
        this.imgSelected2 = this.webView.convertFileSrc(imageData);
        this.form.controls.profile_photo.setValue(imageData)
        this.imgUri = imageData;
        console.log("imagen" , imageData)
        console.log("image frente",this.form)
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

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }


  async modal(event){
    // console.log(event)
  
    const modal = await this.modalController.create({
      component: ModalMedidasPage,
      cssClass: 'medida-modal',
      componentProps:{
        nombre: event
      }
    })
  
    await modal.present();

  }


}