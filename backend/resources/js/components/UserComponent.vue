<template>
<div>

	<h2>Usuarios</h2>
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


	<div class="card card-danger animated  fadeIn" v-if=!cargando>
              <div class="card-header">
                <h3 class="card-title">Lista de Usuarios:</h3>
              </div>
              <div class="card-body">
              	<table id="table" class="stripe hover row-border order-column" style="width:100%">
			        <thead>
			            <tr>
			                <th>Nombre</th>
			                <th>Email</th>
			                <th>Sexo</th>
			                <th>Objetivo</th>
			                <th>Estatus</th>
			            </tr>
			        </thead>
			        <tbody>
			            <tr  v-for="(item, index) in users" :key="index" >
			                <td>{{item.name}}</td>
			                <td>{{item.email}}</td>
			                <td v-if="item.gender == 0"> Femenino</td>
			                <td v-else="item.gender == 1"> Masculino</td>
			                <td v-if="item.objective == 0"> Estar en forma</td>
			                <td v-if="item.objective == 1"> Ganas Musculos</td>
			                <td v-if="item.objective == 2"> Perder Peso</td>
			                <td class="alert alert-success text-center"> Activo</td>
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
						users:[],
						user: {id: '', email: '', name: '', gender: '', objective: '', act: ''}
					}
				}, 
				methods:{
					  mytable(){
		                 $(function() {
		                 $('#table').DataTable(({
					    	"language": {
					      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
					    },"aoColumnDefs": [{
					         "bSortable": false
					       }]
					    }));

		               });  
		               	this.cargando=false; 
		            },
					created(){
						
					    axios.get('/fittech/user').then(res=>{
					      this.users = res.data;
					      this.mytable()
					      this.cargando=false;

					    })
					  }/*,

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
				      axios.post(`/exercise`, formData,{
		                  headers: {
		                    'Content-Type': 'multipart/form-data'
		                  }
		               })
						.then(res => {
							$('#table').DataTable().destroy();
							this.created();
							this.operacion=true;
						})
					}
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
				      axios.post(`/exercise/${exercise.id}`, formData,{
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
				        axios.delete(`/exercise/${exercise.id}`)
				          .then(()=>{
				            $('#table').DataTable().destroy();
							this.created();
							this.operacion=true;
				          })
				      }
		   			 },

		            guardarVideo (event) {
		               this.video = event.target.files[0];
		            }*/
        }
		

	}
</script>