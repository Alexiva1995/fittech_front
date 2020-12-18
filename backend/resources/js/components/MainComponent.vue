<template>
  <div class="hold-transition ">

    <div class="card card-danger" v-if="operacion==false">
              <div class="card-header">
                <h3 class="card-title">Fittech247.com</h3>
              </div>
    </div>


    <div v-if="operacion==false" class="animated  fadeIn ">
      <div class="login-box">
        <div class="login-logo ">
          <img src="adminlte/dist/img/fittech1.jpg" width="60%" height="50%">
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg">Inicia sesión para comenzar tu sesión</p>
            <hr>
            <form  @submit.prevent="login" method="post">
              <div class="form-group has-feedback">
                <label>Email</label>
                <input type="email" class="form-control" placeholder="Email" v-model="user.correo">
              </div>
              <div class="form-group has-feedback">
                <label>Password</label>
                <input type="password" class="form-control" placeholder="Password" v-model="user.clave">
              </div>
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-danger btn-block btn-flat">Iniciar Sesión</button>
                </div>
                <div class=" col-md-12 alert text-danger alert-dismissible animated fadeIn" v-if="lg==false">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <i class="icon fa fa-exclamation"> Usuario o Clave Incorrecta!</i>
                </div>
                <!-- /.col -->
              </div>
            </form>
          </div>
          <!-- /.login-card-body -->
        </div>
      </div>
    </div>

    <div v-if="operacion==true" class="animated  fadeIn">
     <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
          </li>
        </ul>
     </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
        <img src="adminlte/dist/img/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
               Fittech247
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="adminlte/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">Administrador</a>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->

              <li class="nav-header">Menú de Opciones</li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-home"></i>
                  <p>Home</p>
                </a>
              </li>
              <li class="nav-item">
                <router-link :to="{name: 'user'}">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-users"></i>
                  <p class="text">Usuarios</p>
                </a>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link :to="{name: 'plan'}">
                  <a href="#" class="nav-link">
                   <i class="nav-icon fa fa-ticket"></i>
                   Planes
                  </a>
                </router-link>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-shopping-bag"></i>
                  <p>Productos</p>
                </a>
              </li>
              <li class="nav-item">
                <router-link :to="{name: 'exercise'}">
                  <a href="#" class="nav-link">
                   <i class="nav-icon fa fa-cubes"></i>
                   Ejercicios
                  </a>
                </router-link>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-cutlery"></i>
                  <p>Comidas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-calendar-minus-o"></i>
                  <p>Ordenes</p>
                </a>
              </li>
              <li class="nav-item" @click="cerrarSesion()">
                <a href="#" class="nav-link">
                  <i class="nav-icon fa fa-lock"></i>
                  <p>Cerrar Sesión</p>
                </a>
              </li>
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class=""><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">{{$route.fullPath}}</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3>150</h3>

                    <p>New Orders</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>

                    <p>Bounce Rate</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3>44</h3>

                    <p>User Registrations</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person-add"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3>65</h3>

                    <p>Unique Visitors</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div>
              <hr>
              <section class="connectedSortable">
                  <transition name="slide-fade" mode="out-in">
                    <router-view :key="$route.fullPath"></router-view>
                  </transition>
              </section>

              <!-- right col -->
            </div>
            <!-- /.row (main row) -->
          </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2018 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
              <b>Version</b> 3.0.0-alpha
            </div>
          </footer>

          <!-- Control Sidebar -->
          <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
          </aside>
      
    </div>
 

     </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
          return{
            operacion: false,
            lg: true,
            user: {correo: '', clave: ''}
          }
        },methods:{

          login(){

              if(this.user.correo.trim() === '' || this.user.clave.trim() === ''){
                  alert('Debes completar todos los campos antes de guardar');
                  return;
                 }else{
                      if(this.user.correo.trim() === 'admin@gmail.com' && this.user.clave.trim() === '123456'){
                      this.user.correo= '';
                      this.user.clave= '';
                      this.operacion=true;
                      return;                  
                    }else{
                      this.lg=false;
                      return; 
                    }

                 }

          },cerrarSesion(){
                  this.operacion=false;
          }
        }
    }
</script>
