import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-alimento-paso4',
  templateUrl: './tutorial-alimento-paso4.component.html',
  styleUrls: ['./tutorial-alimento-paso4.component.scss'],
})
export class TutorialAlimentoPaso4Component implements OnInit {

  constructor(private ruta:NavController) { }
  video2:any
  video3:any
  video4:any

  ngOnInit() {
    this.video2 = `http://fittech247.com/fittech/videos/Tutoriales/t5.mp4`
    this.video3 = `http://fittech247.com/fittech/videos/Tutoriales/t3.mp4`
    this.video4 = `http://fittech247.com/fittech/videos/Tutoriales/t4.mp4`
  }

  go(){
    this.ruta.navigateForward('bateria-alimento')
  }

}
