import { Component, OnInit } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { NavController } from '@ionic/angular';
import { NutricionService } from '../services/nutricion.service';
import { MensajesService } from '../services/mensajes.service';

@Component({
  selector: 'app-lineanutricional',
  templateUrl: './lineanutricional.page.html',
  styleUrls: ['./lineanutricional.page.scss'],
})
export class LineanutricionalPage implements OnInit {

  formNutrition: FormGroup;
  option1:boolean = false;
  option2:boolean = false;
  option3:boolean = false;
  option4:boolean = false;
  constructor(private fb: FormBuilder,
              private ruta: NavController,
              private service: NutricionService,
              private utilities: MensajesService) { 
    this.formNutrition = this.fb.group({
      name: ['', Validators.required],
    });
  }

  ngOnInit() {
  } 

    show(id){
      switch (id) {
        case 0:
          {
            this.option1 = true;
            this.option2 = false;
            this.option3 = false;
            this.option4 = false;
             this.formNutrition.controls.name.setValue(id);
            break;
        }
        case 1:
          {
            this.option1 = false;
            this.option2 = true;
            this.option3 = false;
            this.option4 = false;
             this.formNutrition.controls.name.setValue(id);
            break;
        }

        case 2:
          {
            this.option1 = false;
            this.option2 = false;
            this.option3 = true;
            this.option4 = false;
             this.formNutrition.controls.name.setValue(id);
            break;
        }

        case 3:
          {
            this.option4 = true;
            this.option1 = false;
            this.option2 = false;
            this.option3 = false;
             this.formNutrition.controls.name.setValue(id);
            break;
        }
      }
    }

    goTo(url:string){
      this.ruta.navigateForward(url);
    }

    async updateTypeFood(){
      await this.service.updateTypeFood(this.formNutrition.value).then((res)=>{
        this.goTo('/alimentos-no-like')
      },
      (err)=>{
        this.utilities.notificacionUsuario('Disculpe, Ha ocurrido un error', 'danger')
      })
    }

    atras(){
      this.ruta.pop();
    }
}
