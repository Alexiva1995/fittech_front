import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';

@Component({
  selector: 'app-tutorial-alimento-paso3',
  templateUrl: './tutorial-alimento-paso3.component.html',
  styleUrls: ['./tutorial-alimento-paso3.component.scss'],
})
export class TutorialAlimentoPaso3Component implements OnInit {
  video1:any
  pasar:any;

  constructor(private apiService:ApiFitechService,private ruta:NavController) { }

  ngOnInit() {
    this.video1 = `http://fittech247.com/fittech/videos/Tutoriales/t1.mp4`
  }


  saltar(){
    this.apiService.guardartutorial(true)
    this.ruta.navigateRoot(['/bateria-alimento'])
  }

}
