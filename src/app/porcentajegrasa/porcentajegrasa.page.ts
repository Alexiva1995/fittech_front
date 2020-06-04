import { Component, OnInit, ViewChild } from '@angular/core';
import { IonSlides, LoadingController } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';
import { ApiFitechService } from '../services/api-fitech.service';
@Component({
  selector: 'app-porcentajegrasa',
  templateUrl: './porcentajegrasa.page.html',
  styleUrls: ['./porcentajegrasa.page.scss'],
})
export class PorcentajegrasaPage implements OnInit {
  @ViewChild('slide' , {static: true} ) slides: IonSlides;
   slideOpts = {
    slidesPerView: 1,
    coverflowEffect: {
      rotate: 50,
      stretch: 0,
      depth: 100,
      modifier: 1,
      slideShadows: true,
    },
    on: {
      beforeInit() {
        const swiper = this;
  
        swiper.classNames.push(`${swiper.params.containerModifierClass}coverflow`);
        swiper.classNames.push(`${swiper.params.containerModifierClass}3d`);
  
        swiper.params.watchSlidesProgress = true;
        swiper.originalParams.watchSlidesProgress = true;
      },
      setTranslate() {
        const swiper = this;
        const {
          width: swiperWidth, height: swiperHeight, slides, $wrapperEl, slidesSizesGrid, $
        } = swiper;
        const params = swiper.params.coverflowEffect;
        const isHorizontal = swiper.isHorizontal();
        const transform$$1 = swiper.translate;
        const center = isHorizontal ? -transform$$1 + (swiperWidth / 2) : -transform$$1 + (swiperHeight / 2);
        const rotate = isHorizontal ? params.rotate : -params.rotate;
        const translate = params.depth;
        // Each slide offset from center
        for (let i = 0, length = slides.length; i < length; i += 1) {
          const $slideEl = slides.eq(i);
          const slideSize = slidesSizesGrid[i];
          const slideOffset = $slideEl[0].swiperSlideOffset;
          const offsetMultiplier = ((center - slideOffset - (slideSize / 2)) / slideSize) * params.modifier;
  
           let rotateY = isHorizontal ? rotate * offsetMultiplier : 0;
          let rotateX = isHorizontal ? 0 : rotate * offsetMultiplier;
          // var rotateZ = 0
          let translateZ = -translate * Math.abs(offsetMultiplier);
  
           let translateY = isHorizontal ? 0 : params.stretch * (offsetMultiplier);
          let translateX = isHorizontal ? params.stretch * (offsetMultiplier) : 0;
  
           // Fix for ultra small values
          if (Math.abs(translateX) < 0.001) translateX = 0;
          if (Math.abs(translateY) < 0.001) translateY = 0;
          if (Math.abs(translateZ) < 0.001) translateZ = 0;
          if (Math.abs(rotateY) < 0.001) rotateY = 0;
          if (Math.abs(rotateX) < 0.001) rotateX = 0;
  
           const slideTransform = `translate3d(${translateX}px,${translateY}px,${translateZ}px)  rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
  
           $slideEl.transform(slideTransform);
          $slideEl[0].style.zIndex = -Math.abs(Math.round(offsetMultiplier)) + 1;
          if (params.slideShadows) {
            // Set shadows
            let $shadowBeforeEl = isHorizontal ? $slideEl.find('.swiper-slide-shadow-left') : $slideEl.find('.swiper-slide-shadow-top');
            let $shadowAfterEl = isHorizontal ? $slideEl.find('.swiper-slide-shadow-right') : $slideEl.find('.swiper-slide-shadow-bottom');
            if ($shadowBeforeEl.length === 0) {
              $shadowBeforeEl = swiper.$(`<div class="swiper-slide-shadow-${isHorizontal ? 'left' : 'top'}"></div>`);
              $slideEl.append($shadowBeforeEl);
            }
            if ($shadowAfterEl.length === 0) {
              $shadowAfterEl = swiper.$(`<div class="swiper-slide-shadow-${isHorizontal ? 'right' : 'bottom'}"></div>`);
              $slideEl.append($shadowAfterEl);
            }
            if ($shadowBeforeEl.length) $shadowBeforeEl[0].style.opacity = offsetMultiplier > 0 ? offsetMultiplier : 0;
            if ($shadowAfterEl.length) $shadowAfterEl[0].style.opacity = (-offsetMultiplier) > 0 ? -offsetMultiplier : 0;
          }
        }
  
         // Set correct perspective for IE10
        if (swiper.support.pointerEvents || swiper.support.prefixedPointerEvents) {
          const ws = $wrapperEl[0].style;
          ws.perspectiveOrigin = `${center}px 50%`;
        }
      },
      setTransition(duration) {
        const swiper = this;
        swiper.slides
          .transition(duration)
          .find('.swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left')
          .transition(duration);
      }
    }
  }
  contador:number = 1;
  grasa:number
  validar:any
  genero:number
  index: number;
  constructor( private ruta: NavController,
              private service: NutricionService,
              private utilities: MensajesService,
              private apiService:ApiFitechService,
              public loadingController: LoadingController) { }

  async ngOnInit() {
    const valor = await this.apiService.cargarNombreUsuario()
    this.genero = valor['gender'];
    console.log("genero",this.genero)
  }
  siguiente(){
    this.contador++;
    this.slides.slideNext();
  }

  async goTo(url:string){
    // esperar
    this.presentLoading()
    // funcion para detectar grasa
    this.nivelGrasa()
    // respueta del servidor
     this.validar = await this.service.grease(this.grasa) 
     this.loadingController.dismiss()
    //  validacion
       if(this.validar){
        this.ruta.navigateForward(url);
       }else{
        this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
       }
  }

  atras(){
    this.contador--;
    this.slides.slidePrev();
  }

  async getPercent(){
    this.index = await this.slides.getActiveIndex();
    return this.index ;
  }

  nivelGrasa(){
    if(this.contador === 1){
      this.grasa = 3
    }
    if(this.contador === 2){
      this.grasa = 9
    }
    if(this.contador === 3){
      this.grasa = 13
    }
    if(this.contador === 4){
      this.grasa = 16
    }
    if(this.contador === 5){
      this.grasa = 21
    }
    if(this.contador === 6){
      this.grasa = 26
    }
    if(this.contador === 7){
      this.grasa = 31
    }
    if(this.contador === 8){
      this.grasa = 40
    }
    if(this.contador === 9){
      this.grasa = 45
    }

  }

  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Por favor espere...',
    });
    await loading.present();
  }

  devolver(){
    this.ruta.pop();
  }

}
