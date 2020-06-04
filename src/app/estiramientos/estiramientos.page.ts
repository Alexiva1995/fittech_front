import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-estiramientos',
  templateUrl: './estiramientos.page.html',
  styleUrls: ['./estiramientos.page.scss'],
})
export class EstiramientosPage implements OnInit {
  estiramientos: boolean = false;
  constructor() { }

  ngOnInit() {
  }

  iniciar(){
    this.estiramientos = !this.estiramientos;
  }
}
