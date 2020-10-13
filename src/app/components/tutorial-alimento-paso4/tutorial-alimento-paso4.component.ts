import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';

@Component({
  selector: 'app-tutorial-alimento-paso4',
  templateUrl: './tutorial-alimento-paso4.component.html',
  styleUrls: ['./tutorial-alimento-paso4.component.scss'],
})
export class TutorialAlimentoPaso4Component implements OnInit {

  constructor(private apiService:ApiFitechService,private ruta:NavController) { }
  video2:any
  video3:any
  video4:any
  pasar:any;


  ngOnInit() {
    this.video2 = `http://fittech247.com/fittech/videos/Tutoriales/t5.mp4`
    this.video3 = `http://fittech247.com/fittech/videos/Tutoriales/t3.mp4`
    this.video4 = `http://fittech247.com/fittech/videos/Tutoriales/t4.mp4`
  }

  saltar(){
    this.apiService.guardartutorial(true)
    this.ruta.navigateRoot(['/bateria-alimento'])
  }

}
