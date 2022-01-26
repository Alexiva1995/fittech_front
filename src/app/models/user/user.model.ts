import { MBaseModel } from '../base-model/base.model';
import { IUser } from './user.interface';

export class MUser extends MBaseModel {

    public activity: number = null;
    public physicalActivity: number = null;
    public age: number = null;
    public avatar: string = null;
    public email: string = null;
    public fcMax: number = null;
    public fcMin: number = null;
    public feedingType: number = null;
    public gender: number = null;
    public heartRate: any = null;
    public ica: any = null;
    public id: number = null;
    public imc: number = null;
    public imcIndicator: string = null;
    public name: string = null;
    public obesityCc: any = null;
    public objective: number = null;
    public pushIdToken: any = null;
    public pushToken: any = null;
    public risk: any = null;
    public stature: number = null;
    public status: number = null;
    public trainingExperience: number = null;
    public trainingPlace: number = null;
    public weight: number = null;
    
    private createdAt: Date = null;
    private updatedAt: Date = null;
    private emailVerifiedAt: Date = null;

    constructor(user: IUser = null){
        super()

        if (user) {
            this.activity = user.act;
            this.physicalActivity = user.act_physical;
            this.avatar = user.avatar;
            this.age = user.age;
            this.avatar = user.avatar;
            this.email = user.email;
            this.fcMax = user.fcmax;
            this.fcMin = user.fcmin;
            this.feedingType = user.feeding_type;
            this.gender = user.gender;
            this.heartRate = user.heart_rate;
            this.ica = user.ica;
            this.id = user.id;
            this.imc = user.imc;
            this.imcIndicator = user.indicator_imc;
            this.name = user.name;
            this.obesityCc = user.obesity_cc;
            this.objective = user.objective;
            this.pushIdToken = user.pushIdToken;
            this.pushToken = user.pushToken;
            this.risk = user.risk;
            this.stature = user.stature;
            this.status = user.status;
            this.trainingExperience = user.training_experience;
            this.trainingPlace = user.training_place;
            this.weight = user.weight;
            this.createdAt = user.created_at ? new Date(user.created_at) : null;
            this.updatedAt = user.updated_at ? new Date(user.updated_at) : null;
            this.emailVerifiedAt = user.email_verified_at ? new Date(user.email_verified_at) : null;
        } else {
            this.isNew = true;
        }
    }

    public returnInterface(): IUser{
        return {
            act: this.activity,
            act_physical: this.physicalActivity,
            age: this.age,
            avatar: this.avatar,
            email: this.email,
            created_at: this.returnDate(this.createdAt),
            email_verified_at: this.returnDate(this.emailVerifiedAt),
            fcmax: this.fcMax,
            fcmin: this.fcMin,
            feeding_type: this.feedingType,
            gender: this.gender,
            heart_rate: this.heartRate,
            ica: this.ica,
            id: this.id,
            imc: this.imc,
            indicator_imc: this.imcIndicator,
            name: this.name,
            obesity_cc: this.obesityCc,
            objective: this.objective,
            pushIdToken: this.pushIdToken,
            pushToken: this.pushToken,
            risk: this.risk,
            stature: this.stature,
            status: this.status,
            training_experience: this.trainingExperience,
            training_place: this.trainingPlace,
            updated_at: this.returnDate(this.updatedAt),
            weight: this.weight
        }
    }
}