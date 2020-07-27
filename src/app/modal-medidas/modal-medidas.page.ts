import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-modal-medidas',
  templateUrl: './modal-medidas.page.html',
  styleUrls: ['./modal-medidas.page.scss'],
})
export class ModalMedidasPage implements OnInit {

  constructor(public modalController: ModalController) { }

  ngOnInit() {
  }

  salir(){
    this.modalController.dismiss()
  }

}
