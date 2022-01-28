import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { ApiFitechService } from 'src/app/services/api-fitech.service';
import { NavController, AlertController, IonSlides, PopoverController, IonContent } from '@ionic/angular';
import { MUser } from 'src/app/models/user/user.model';
import { IMCPopoverComponent } from './components/imc-popover/imc-popover.component';
import { MIndicator } from 'src/app/models/indicator/indicator.model';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.page.html',
  styleUrls: ['./perfil.page.scss'],
})
export class PerfilPage implements OnInit {

  constructor( 
    private apiService:ApiFitechService,
    private ruta:NavController,
    private alert: AlertController,
    private popOver: PopoverController
  ) { }

  @ViewChild('mySlides', {static: false}) public element: IonContent;
  @ViewChild('slides', {static: false}) public ionSlides: IonSlides;
  public user: MUser = null;
  public slideIndex: number = 0;
  public password: string = "";
  public confirmPassword: string = "";
  public newPassword: string = "";
  public indicator: MIndicator = null;
  public enterClass: boolean = true;
  public slideOptions = {
    initialSlide: 0,
    slidesPerView: 2.8,
    spaceBetween: 0
  }

  async ngOnInit() {
    this.user = this.apiService.getUserData();
    await this.ionSlides
    this.ionSlides.lockSwipeToNext(true);
    this.ionSlides.lockSwipeToPrev(true);
    console.log(this.user);
    this.apiService.getIndicators().subscribe(res => {
      console.log(res.data)
      this.indicator = res.data
    })
  }

  public async logOut(){
    // LLAMO ALA RUTA PARA DESCONECTAR Y LO FUERZO A REDIRECIONAR AL LOGIN
    const a = await this.alert.create({
      // header: 'Cerrar sesión',
      subHeader: `¿Esta segur${this.user.gender ? 'o' : 'a'} que desea cerrar su sesión?`,
      buttons: [{
        text: 'Aceptar',
        role: 'accept'
      }, {
        text: 'Cancelar',
        role: 'cancel'
      }]
    })

    await a.present()

    const { role } = await a.onDidDismiss();

    if (role == 'accept') {
      this.apiService.desconectarUsuario()
      this.ruta.navigateRoot(["/"])
    }
  }

  public changeSlide(slide: number, slides: IonSlides){
    this.ionSlides.lockSwipeToNext(false);
    this.ionSlides.lockSwipeToPrev(false);
    slides.slideTo((slide + 0.5));
    this.ionSlides.slideTo(slide);
    if (slide > this.slideIndex) {
      this.enterClass = false;
    } else {
      if (slide < this.slideIndex) {
        this.enterClass = true;
      }
    }
    this.slideIndex = slide;
    this.ionSlides.lockSwipeToNext(true);
    this.ionSlides.lockSwipeToPrev(true);
    this.element.scrollToTop(100);
  }

  public async showPopover(){
    const popover = await this.popOver.create({
      component: IMCPopoverComponent,
    })

    await popover.present()

    const res = await popover.onDidDismiss();
  }


}
