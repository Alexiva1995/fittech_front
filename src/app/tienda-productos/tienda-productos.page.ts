import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { LoadingController } from '@ionic/angular';
import { MensajesService } from '../services/mensajes.service';
import { TiendaService } from '../services/tienda.service';

@Component({
  selector: 'app-tienda-productos',
  templateUrl: './tienda-productos.page.html',
  styleUrls: ['./tienda-productos.page.scss'],
})
export class TiendaProductosPage implements OnInit {
  productos:any = []
  buscador:any = []
  name:any
  searchTerm: any;

  constructor( private service: TiendaService,
              private route: ActivatedRoute,
              public loadingController: LoadingController,
              private utilities: MensajesService,) {
                this.route.queryParams.subscribe(params => {
                  this.productos = JSON.parse(params["shop"]);
                  this.name = this.productos.name
                  this.productos = this.productos.product
                  this.buscador = this.productos
                  });
               }

  ngOnInit() {
  }

  search(){
     if(!this.searchTerm){
        this.productos = this.buscador
     }else{
       this.productos = this.filterItems()
     }
  }

  filterItems(){
    return this.productos.filter((item) => {
      console.log(item);
      return (
        item.name.toLowerCase().indexOf(this.searchTerm.toLowerCase()) > -1 
      );
    });
  }

}
