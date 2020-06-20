import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { ApiFitechService } from './api-fitech.service';
import { environment } from 'src/environments/environment';
const URL  = environment.url

@Injectable({
  providedIn: 'root'
})
export class NutricionService {
  listadocomida={
    desayuno:null,
    almuerzo:null,
    cena:null,
    snack:null
  }

  constructor(private http: HttpClient, private service: ApiFitechService) { }
  // nivel de grasa
  grease(valor){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })
      const data = {
        grease : valor
      }      
      this.http.post(`${URL}/auth/grease_record`,data,{headers})
          .subscribe(resp=>{
          
            resolve(true)
          },err=>{
            reject(false)
          })
      })

  }
  // tipo de alimento
  updateTypeFood(valor){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })

      const data = {
        feeding_type : valor
      }  

      //      this.http.get(`${URL}/auth/routine`,{headers})
      
      this.http.post(`${URL}/auth/update-type-food`, data, {headers})
          .subscribe(resp=>{
            console.log(resp)
            resolve(true)
          },err=>{
            reject(false)
          })
      })

  }
  // comida no deseada
  foodNoDeseados(valor){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Accept':'application/x-www-form-urlencoded',
        'Content-Type':'application/json',
      })

      const data = {
        foods : valor
      }

      this.http.post(`${URL}/auth/foods-not-like`,data,{headers})
          .subscribe(resp=>{
            console.log(resp)
            resolve(true)
          },err=>{
            reject(false)
          })
      })

  }
  // listado de alimentos
  getFoods(){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })
      // si no se envia un dato no  funciona la ruta
      const data = {
        valor : "ignorar"
      }      
      this.http.post(`http://fittech247.com/fittech/api/auth/foods`,data,{headers})
          .subscribe(resp=>{
            resolve(resp['Alimentos'])
          },err=>{
            reject(false)
          })
      })
  }

  // calcular el menu del usuario
  calculate_menu(){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })      
      // si no se envia un dato no  funciona la ruta
      const data = {
        valor : "ignorar"
      }    
      this.http.post(`${URL}/auth/calculate_menu`,data,{headers})
          .subscribe(resp=>{
            console.log(resp)
            resolve(true)
          },err=>{
            reject(false)
          })
      })

  }

  storeMenu(menu){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })   
      this.http.post(`${URL}/auth/store-menu`, menu, {headers})
          .subscribe(resp=>{
            resolve(resp)
          },err=>{
            reject(err)
          })
      })

  }

  //Calculos de indicadores
  indicadores(){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })      
      // si no se envia un dato no  funciona la ruta
      const data = {
        valor : "ignorar"
      }    
      this.http.post(`${URL}/auth/indicators`,data,{headers})
          .subscribe(resp=>{
            resolve(resp)
          },err=>{
            reject(false)
          })
      })

  }

  // listado de nutriente necesario  para consumir del usuario / esto va en resumen
  getResumes(){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })
      // si no se envia un dato no  funciona la ruta
      const data = {
        valor : "ignorar"
      }      
      this.http.post(`${URL}/auth/resume-food`,data,{headers})
          .subscribe(resp=>{
            resolve(resp)
          },err=>{
            reject(false)
          })
      })
  }

  // TRAE EL MENU DEL USUARIO DESAYUNO,ALMUERZO,CENA PARA ESCOGER
  menu(comida:any){
    return new Promise( async (resolve, reject)  => {
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + await this.service.cargarToken(),
        'Content-Type':'application/json',
      })

      // si no se envia un dato no  funciona la ruta
      const data = {
        type_food : comida
      }   
      
      this.http.post(`${URL}/auth/menu`,data,{headers})
          .subscribe(resp=>{
            resolve(resp)
          },err=>{
            reject(false)
          })
      })

  }

  // TREA EL LISTADO DE ALIMENTOS GUARDADOS ( ACTUALIZAR A FUTURO)
  Listadomenu(tipodealimento,menu){
      if(tipodealimento == 2){
        this.listadocomida.almuerzo = menu
      }

      if(tipodealimento == 1){
        this.listadocomida.snack = menu
      }

      if(tipodealimento == 3){
        this.listadocomida.cena = menu
      }

      if(tipodealimento == 0){
        this.listadocomida.desayuno = menu
      }
      console.log("guardado", this.listadocomida)
  }


}
