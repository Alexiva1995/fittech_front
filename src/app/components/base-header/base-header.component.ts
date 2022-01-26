import { Component, Input, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'base-header',
  templateUrl: './base-header.component.html',
  styleUrls: ['./base-header.component.scss'],
})
export class BaseHeaderComponent implements OnInit {

  @Input() public title: string = "- - - -"

  constructor(
    private router: NavController
  ) { }

  ngOnInit() {}

  public return(){
    this.router.pop();
  }

}
