import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-alimento-paso4',
  templateUrl: './tutorial-alimento-paso4.component.html',
  styleUrls: ['./tutorial-alimento-paso4.component.scss'],
})
export class TutorialAlimentoPaso4Component implements OnInit {

  constructor(private ruta:NavController) { }

  ngOnInit() {}

  go(){
    this.ruta.navigateForward('bateria-alimento')
  }

}
