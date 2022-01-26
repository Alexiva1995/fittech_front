import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { NavController } from '@ionic/angular';
import { ApiFitechService } from '../services/api-fitech.service';

@Injectable({
  providedIn: 'root'
})
export class HomeGuard implements CanActivate {
  constructor(
    private navCtrl: NavController,
    private service: ApiFitechService
) { }

  async canActivate() {

    //Validamos que existe un usuario en el localstorage almacenado
    const token = await this.service.cargarToken();
    
    if(!token){
        this.navCtrl.navigateRoot('/auth/login');
        return false;
    }else{
      const user = await this.service.getUserDataFromStorage()
      this.service.setUserData(user)
      return true;
    }
  }
}