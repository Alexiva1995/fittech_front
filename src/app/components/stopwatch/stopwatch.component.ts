import { Component, EventEmitter, OnInit, Output } from '@angular/core';

@Component({
  selector: 'stopwatch',
  templateUrl: './stopwatch.component.html',
  styleUrls: ['./stopwatch.component.scss'],
})
export class StopwatchComponent implements OnInit {

  constructor() { }

  public counter: number = 10;
  public active: boolean = false;
  public i: any

  @Output() completed = new EventEmitter<boolean>();
  @Output() stopped = new EventEmitter<boolean>();

  ngOnInit() {}

  public changeCounter(){
    this.i = setInterval(() => {
      if (this.active && this.counter >= 0) {
        this.counter--
        if (this.counter == 0) {
          this.active = false;
          clearInterval(this.i);
          this.completed.emit(true);
        }
      } 
    }, 1000)
  }

  public play(){
    this.counter = 10;
    setTimeout(() => {
      this.active = !this.active
      if (this.active) {
        this.changeCounter();
      } else {
        clearInterval(this.i);
        this.stopped.emit(true);
      }
    }, 1);
  }

}
