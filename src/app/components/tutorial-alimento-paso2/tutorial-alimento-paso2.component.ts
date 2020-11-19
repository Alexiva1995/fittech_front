import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from 'src/app/services/api-fitech.service';

@Component({
  selector: 'app-tutorial-alimento-paso2',
  templateUrl: './tutorial-alimento-paso2.component.html',
  styleUrls: ['./tutorial-alimento-paso2.component.scss'],
})
export class TutorialAlimentoPaso2Component implements OnInit {

  pasar:any;

  constructor(private apiService:ApiFitechService,private ruta:NavController) { }

   ngOnInit() {
  }

  saltar(){
    this.apiService.guardartutorial(true)
    this.ruta.navigateRoot(['/bateria-alimento'])
  }

}
