export class MBaseModel {

    public isNew: boolean = false;
    public error: boolean = false;

    constructor() {} 

    public returnDate(n: Date = null): string{
        if (n) {
            let str = `${n.getFullYear()}-${n.getMonth() + 1}-${n.getDate()} ${n.getHours()}:${n.getMinutes()}:${n.getSeconds()}`;
            return str;
        } else {
            null
        }
    }
}