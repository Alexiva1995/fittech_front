import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-planes-paso2',
  templateUrl: './tutorial-planes-paso2.component.html',
  styleUrls: ['./tutorial-planes-paso2.component.scss'],
})
export class TutorialPlanesPaso2Component implements OnInit {

  constructor(private ruta:NavController) { }

  ngOnInit() {}
  omitir(){
    this.ruta.navigateRoot(['/'])
  }
}
