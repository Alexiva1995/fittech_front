import { MBaseModel } from '../base-model/base.model';
import { IIndicator } from './indicator.interface';

export class MIndicator extends MBaseModel{

    public imc: number = null;
    public ica: number = null;
    public tmb: number = null;
    public strategy: number = null;
    public waistPerimeter: number = null;

    constructor(ind: IIndicator = null){
        super()
        if (ind) {
            this.imc = ind.imc;
            this.ica = ind.ica;
            this.tmb = ind.tmb;
            this.strategy = ind.Estrategia_N;
            this.waistPerimeter = ind.Perimetro_Cintura;
        }
    }

    public interface(): IIndicator{
        return {
            imc: this.imc,
            Estrategia_N: this.strategy,
            Perimetro_Cintura: this.waistPerimeter,
            ica: this.ica,
            tmb: this.tmb
        }
    }

}