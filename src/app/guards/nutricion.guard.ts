import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from '../services/api-fitech.service';

@Injectable({
  providedIn: 'root'
})
export class NutricionGuard implements CanActivate {
  constructor(
    private navCtrl: NavController,
    private service: ApiFitechService
) { }

  async canActivate() {

    //Validamos que exista la nutricion 
    const nutricion = await this.service.cargarnutricion()
      console.log(nutricion)
    
        if(nutricion === 'activado'){
            console.log("puede pasar")
            return true;
        }else{
          this.navCtrl.navigateForward('/actividad');
          return false;
        }
}
  
}
