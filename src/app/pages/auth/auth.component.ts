import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.scss'],
})
export class AuthComponent implements OnInit {

  constructor(
    private router: Router,
    private nav: NavController
  ) { }

  ngOnInit() { }

  public getRoute(): string{
    return this.router.url;
  }

  return(){
    this.nav.pop()
  }

}
