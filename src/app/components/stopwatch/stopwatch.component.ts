import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

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

  @Input() initialValue: number = 10;
  @Output() completed = new EventEmitter<boolean>();
  @Output() stopped = new EventEmitter<boolean>();

  ngOnInit() {
    this.counter = this.initialValue
  }

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
    this.counter = this.initialValue;
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
