import { Component, OnInit, ViewChild } from '@angular/core';
import { IonSlides } from '@ionic/angular';

@Component({
  selector: 'app-tutorial-planes',
  templateUrl: './tutorial-planes.page.html',
  styleUrls: ['./tutorial-planes.page.scss'],
})
export class TutorialPlanesPage implements OnInit {
  @ViewChild('slide' , {static: true} ) slides: IonSlides;

  slideOpts = {
    initialSlide: 0,
    speed: 400
  };
  
  constructor() { }

  ngOnInit() {
  }

}
