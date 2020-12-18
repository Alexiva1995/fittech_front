<template>
<div>

	<h2>Ejercicios</h2>
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
                <h3 class="card-title">Agregar Ejercicio</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="display: none;">
				<form @submit.prevent="agregar">
					<h3>Nuevo Ejercicio</h3>
					<div class="row">
						<div class="form-group col-md-3">
						<label>Código</label>
	                    <select class="form-control" v-model="exercise.cod"> 
	                    	<option selected disabled value="">Código</option>
	                    	<option value="B"> B</option>
	                    	<option Value="C"> C</option>
	                    	<option value="CO"> CO</option>
	                    	<option Value="CR"> CR</option>
	                    	<option value="D"> D</option>
	                    	<option Value="E"> E</option>
	                    	<option value="FE"> FE</option>
	                    	<option Value="FL"> FL</option>
	                    	<option value="FP"> FP</option>
	                    	<option Value="FT"> FT</option>
	                    	<option value="G"> G</option>
	                    	<option Value="I"> I</option>
	                    	<option value="P"> P</option>
	                    	<option Value="Pt"> Pt</option>
	                    	<option value="T"> T</option>
	                    	<option Value="TI"> TI</option>
	                    	<option value="TS"> TS</option>
	                    	<option Value="ZM"> ZM</option>
	                    </select>
	                    </div>
	                    <div class="form-group col-md-3">
	                    <label>Nombre</label>
	                    <input type="text" class="form-control" placeholder="Nombre" v-model="exercise.name">
	                    </div>
	                    <div class="form-group col-md-3">
	                    <label>Nivel</label>
	                    <select class="form-control" v-model="exercise.pro"> 
	                    	<option selected disabled value="">Nivel</option>
	                    	<option value="0"> Facil</option>
	                    	<option Value="1"> Difícil</option>
	                    </select>
	                    </div>

	                  	<div class="form-group col-md-3">
	                    	<label>Video</label>
		                    <div class="input-group">
		                      <div class="custom-file">

		                        <input type="file" class="custom-file-input" name="video" accept="video/mp4" @change="guardarVideo">

		                        <label class="custom-file-label">{{this.video.name}}</label>
		                      </div>
		                    </div>
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
                <h3 class="card-title">Editar Ejercicio</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" @click="cerrarFormulario()"><i class="fa fa-times"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body ">
				<form @submit.prevent="editarExercise(exercise)" >
					<h3>Ejercicio:</h3>
					<div class="row">
						<div class="form-group col-md-3">
						<label>Código</label>
	                    <select class="form-control" v-model="exercise.cod"> 
	                    	<option selected disabled value="">Código</option>
	                    	<option value="B"> B</option>
	                    	<option Value="C"> C</option>
	                    	<option value="CO"> CO</option>
	                    	<option Value="CR"> CR</option>
	                    	<option value="D"> D</option>
	                    	<option Value="E"> E</option>
	                    	<option value="FE"> FE</option>
	                    	<option Value="FL"> FL</option>
	                    	<option value="FP"> FP</option>
	                    	<option Value="FT"> FT</option>
	                    	<option value="G"> G</option>
	                    	<option Value="I"> I</option>
	                    	<option value="P"> P</option>
	                    	<option Value="Pt"> Pt</option>
	                    	<option value="T"> T</option>
	                    	<option Value="TI"> TI</option>
	                    	<option value="TS"> TS</option>
	                    	<option Value="ZM"> ZM</option>
	                    </select>
	                    </div>
	                    <div class="form-group col-md-3">
	                    <label>Nombre</label>
	                    <input type="text" class="form-control" placeholder="Nombre" v-model="exercise.name">
	                    </div>
	                    <div class="form-group col-md-3">
	                    <label>Nivel</label>
	                    <select class="form-control" v-model="exercise.pro"> 
	                    	<option selected disabled value="">Nivel</option>
	                    	<option value="0"> Facil</option>
	                    	<option Value="1"> Difícil</option>
	                    </select>
	                    </div>

	                  	<div class="form-group col-md-3">
	                    	<label>Video</label>
		                    <div class="input-group">
		                      <div class="custom-file">

		                        <input type="file" class="custom-file-input" name="video"  accept="video/mp4" @change="guardarVideo">

		                        <label class="custom-file-label">{{this.video.name}}</label>
		                      </div>
		                    </div>
						</div>

					</div>
					<button type="submit" class="btn btn-danger">Guardar cambios</button>
					<button type="reset" class="btn">Borrar</button>
				</form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>

	<div class="card card-danger animated  fadeIn" v-if=!cargando>
              <div class="card-header">
                <h3 class="card-title">Lista de Ejercicios:</h3>
              </div>
              <div class="card-body">
              	<table id="table" class="stripe hover row-border order-column" style="width:100%">
			        <thead>
			            <tr>
			            	<th>Id</th>
			                <th>Cod</th>
			                <th>Nombre</th>
							<th>Imagen</th>
			                <th>Url</th>
			                <th>Pro</th>
			                <th></th>
			                <th></th>
			            </tr>
			        </thead>
			        <tbody>
			            <tr  v-for="(item, index) in exercises" :key="index" >
			                <td>{{item.id}}</td>
			                <td>{{item.cod}}</td>
			                <td>{{item.name}}</td>
			                <td><img :src="'http://fittech247.com/fittech/imagenes/'+item.cod+'/'+item.id+'.jpg'" alt=""></td>
			                <td v-if="item.url"><a :href="'http://fittech247.com/fittech/videos/'+item.cod+'/'+item.id+'.mp4'">Ver</a></td>
			                <td v-else="item.url"> sin video</td>

			                <td>{{item.pro}}</td>
			                <td><a @click="editarFormulario(item)" class="fa fa-pencil-square-o"></a></td>
			                <td><a @click="eliminarExercise(item, index)" class="fa fa-trash"></a></td>
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
						video: '',
						modoEditar: false,
						exercises:[],
						exercise: {id: '',cod: '', name: '', url: '', pro: ''}
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
					         "aTargets": [5,6]
					       }]
					    }));

		               });  
		               	this.cargando=false; 
		            },
					created(){
						
					    axios.get('/fittech/exercise').then(res=>{
					      this.exercises = res.data;
					      this.mytable()
					      this.cargando=false;

					    })
					  },

					agregar(){

						if(this.exercise.name.trim() === '' || this.exercise.cod.trim() === ''){
		        		alert('Debes completar todos los campos antes de guardar');
		        		return;
		     			 }
		     			 this.cargando=true;
		     		     const file = this.video;
		                 const formData = new FormData();

		                 formData.append('name', this.exercise.name);
		                 formData.append('cod', this.exercise.cod);
		                 formData.append('pro', this.exercise.pro);
		                 formData.append('video', file);
						this.exercise.cod= '';
						this.exercise.name= '';
						this.exercise.pro= '';
						this.exercise.url= '';
				      axios.post(`/fittech/exercise`, formData,{
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
					      this.exercise.name = item.name;
					      this.exercise.cod = item.cod;
					      this.exercise.url = item.url;
					      this.exercise.pro = item.pro;
					      this.exercise.id = item.id;
					      this.modoEditar = true;
					    },
					cerrarFormulario(){
					      this.modoEditar = false;
					    },
					editarExercise(exercise){
					  this.cargando=true;
					  const file = this.video;
		              const formData = new FormData();
		              formData.append('video', file);
		              formData.append('_method', 'PUT');
		              formData.append('name', exercise.name);
		              formData.append('cod', exercise.cod);
		              formData.append('pro', exercise.pro);
				      axios.post(`/fittech/exercise/${exercise.id}`, formData,{
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

					eliminarExercise(exercise, index){
					  this.operacion=false;
				      const confirmacion = confirm(`Eliminar exercise ${exercise.name}`);
				      this.cargando=true;
				      if(confirmacion){
				        axios.delete(`/fittech/exercise/${exercise.id}`)
				          .then(()=>{
				            $('#table').DataTable().destroy();
							this.created();
							this.operacion=true;
				          })
				      }
		   			 },

		            guardarVideo (event) {
		               this.video = event.target.files[0];
		            }
        }
		

	}
</script>