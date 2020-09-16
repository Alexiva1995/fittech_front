import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-tutorial-alimentacion',
  templateUrl: './tutorial-alimentacion.page.html',
  styleUrls: ['./tutorial-alimentacion.page.scss'],
})
export class TutorialAlimentacionPage implements OnInit {
  slideOpts = {
    initialSlide: 0,
    speed: 400
  };
  
  constructor() { }

  ngOnInit() {
  }

}
