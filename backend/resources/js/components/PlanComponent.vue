<template>
<div>
    <h2>Planes</h2>
  <br>

  <div class="row text-center" v-if=cargando>
      <div class="col">
          <i class="fa fa-spinner fa-spin fa-2x"></i>
      </div>
  </div>



  <div v-if=!cargando>

  <div class=" col-md-12 alert text-success alert-dismissible animated fadeIn" v-if="operacion">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fa fa-check"> Operación Realizada con Éxito!</i></h5>
  </div>

  <div class="col-md-12">
            <div class="card card-danger card-outline collapsed-card">
              <div class="card-header">
                <h3 class="card-title">Agregar Plan</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="display: none;">
                <form @submit.prevent="agregar">
                  <h3>Nuevo Plan</h3>
                  <div class="row">
                              <div class="form-group col-md-4">
                              <label>Nombre</label>
                              <input type="text" class="form-control" placeholder="Nombre" v-model="plan.name">
                              </div>
                              <div class="form-group col-md-4">
                              <label>Descripción</label>
                              <input type="text" class="form-control" placeholder="Descripcion" v-model="plan.description">
                              </div>
                              <div class="form-group col-md-4">
                              <label>Precio</label>
                              <input type="text" class="form-control" placeholder="Precio" v-model="plan.price">
                              </div>

                  </div>
                  <button type="submit" class="btn btn-danger">Agregar</button>
                  <button type="reset" class="btn">Borrar</button>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
  </div>

      <div class="col-md-12 animated fadeIn" v-if="modoEditar">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Editar Plan</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" @click="cerrarFormulario()"><i class="fa fa-times"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body ">
                <form @submit.prevent="editar(plan)" >
                  <h3>Editar Plan</h3>
                  <div class="row">
                              <div class="form-group col-md-3">
                              <label>Nombre</label>
                              <input type="text" class="form-control" placeholder="Nombre" v-model="plan.name">
                              </div>
                              <div class="form-group col-md-3">
                              <label>Descripcion</label>
                              <input type="text" class="form-control" placeholder="Codigo" v-model="plan.description">
                              </div>
                              <div class="form-group col-md-3">
                              <label>Precio</label>
                              <input type="text" class="form-control" placeholder="Codigo" v-model="plan.price">
                              </div>

                              <div class="form-group col-md-3">

                              </div>

                  </div>
                  <button type="submit" class="btn btn-danger">Guardar Cambios</button>
                  <button type="reset" class="btn">Borrar</button>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>

  <hr>

  <div class="card card-danger animated  fadeIn" v-if=!cargando>
              <div class="card-header">
                <h3 class="card-title">Lista de planes:</h3>
              </div>
              <div class="card-body">
                <table id="table" class="stripe hover row-border order-column" style="width:100%">
              <thead>
                  <tr>
                    <th>Id</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Precio</th>
                      <th></th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  <tr  v-for="(item, index) in planes" :key="index" >
                      <td>{{item.id}}</td>
                      <td>{{item.name}}</td>
                      <td>{{item.description}}</td>
                      <td>{{item.price}}</td>
                      <td><a @click="editarFormulario(item)" class="fa fa-pencil-square-o"></a></td>
                      <td><a @click="eliminar(item, index)" class="fa fa-trash"></a></td>
                  </tr>
              </tbody>
              <tfoot>
              </tfoot>
        </table>
      </div>

    </div>
  </div>
</div>
</template>

<script>

  export default{
     mounted() {
                this.created()
            },
        data(){
          return{
            operacion: false,
            cargando: true,
            modoEditar: false,
            planes:[],
            plan: {id: '',name: '', price: '', description: ''}
          }
        }, 
        methods:{
            mytable(){
                     $(function() {
                     $('#table').DataTable(({
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
              },"aoColumnDefs": [{
                   "bSortable": false, 
                   "aTargets": [4,5]
                 }]
              }));

                   });  
                    this.cargando=false; 
                },
          created(){
            
              axios.get('/fittech/plan').then(res=>{
                this.planes = res.data;
                this.mytable()
                this.cargando=false;

              })
            },

          agregar(){

            if(this.plan.name.trim() === '' || this.plan.price.trim() === ''){
                alert('Debes completar todos los campos antes de guardar');
                return;
               }
               this.cargando=true;
                     const formData = new FormData();
                     formData.append('name', this.plan.name);
                     formData.append('description', this.plan.description);
                     formData.append('price', this.plan.price);
                      this.plan.description= '';
                      this.plan.name= '';
                      this.plan.price= '';
              axios.post(`/fittech/plan`, formData,{
                      headers: {
                        'Content-Type': 'multipart/form-data'
                      }
                   })
            .then(res => {
              $('#table').DataTable().destroy();
              this.created();
              this.operacion=true;
            })
          },
          editarFormulario(item){
              this.operacion=false;
                this.plan.name = item.name;
                this.plan.description = item.description;
                this.plan.price = item.price;
                this.plan.id = item.id;
                this.modoEditar = true;
              },
          cerrarFormulario(){
                this.modoEditar = false;
              },
          editar(plan){
              this.cargando=true;
                  const formData = new FormData();
                  formData.append('name', plan.name);
                  formData.append('description', plan.description);
                  formData.append('price', plan.price);
                  formData.append('_method', 'PUT');
              axios.post(`/fittech/plan/${plan.id}`, formData,{
                      headers: {
                        'Content-Type': 'multipart/form-data'
                      }
                   })
                .then(res=>{
                  this.modoEditar = false;
              $('#table').DataTable().destroy();
              this.created();
              this.operacion=true;
                })
            },

          eliminar(plan, index){
            this.operacion=false;
              const confirmacion = confirm(`Eliminar Plan ${plan.name}`);
              this.cargando=true;
              if(confirmacion){
                axios.delete(`/fittech/plan/${plan.id}`)
                  .then(()=>{
                    $('#table').DataTable().destroy();
              this.created();
              this.operacion=true;
                  })
              }
             },

        }
    

  }
</script>