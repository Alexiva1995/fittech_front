import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'measure',
  templateUrl: './measure.component.html',
  styleUrls: ['./measure.component.scss'],
})
export class MeasureComponent implements OnInit {

  constructor() { }

  @Input() public title: string = "";
  @Input() public last: boolean = false;

  ngOnInit() {}

}
