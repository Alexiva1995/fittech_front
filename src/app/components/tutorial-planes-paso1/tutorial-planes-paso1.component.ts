import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-planes-paso1',
  templateUrl: './tutorial-planes-paso1.component.html',
  styleUrls: ['./tutorial-planes-paso1.component.scss'],
})
export class TutorialPlanesPaso1Component implements OnInit {

  constructor(private ruta:NavController) { }

  ngOnInit() {}

  omitir(){
    this.ruta.navigateRoot(['/'])
  }
}
