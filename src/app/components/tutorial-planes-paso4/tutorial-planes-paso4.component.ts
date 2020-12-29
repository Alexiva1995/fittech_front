import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-planes-paso4',
  templateUrl: './tutorial-planes-paso4.component.html',
  styleUrls: ['./tutorial-planes-paso4.component.scss'],
})
export class TutorialPlanesPaso4Component implements OnInit {

  constructor(private ruta:NavController) { }

  ngOnInit() {}
  omitir(){
    this.ruta.navigateRoot(['/planes-pagos'])
  }
}
