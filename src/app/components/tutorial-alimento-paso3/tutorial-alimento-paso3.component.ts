import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-tutorial-alimento-paso3',
  templateUrl: './tutorial-alimento-paso3.component.html',
  styleUrls: ['./tutorial-alimento-paso3.component.scss'],
})
export class TutorialAlimentoPaso3Component implements OnInit {
  video1:any
  constructor() { }

  ngOnInit() {
    this.video1 = `http://fittech247.com/fittech/videos/Tutoriales/t1.mp4`
  }

}
