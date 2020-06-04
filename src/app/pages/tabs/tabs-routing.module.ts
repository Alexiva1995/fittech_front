import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TabsPage } from './tabs.page';

const routes: Routes = [
  {
    path:'',
    redirectTo:'dashboard'
  },
  {
    path: '',
    component: TabsPage,
    children: [
      {
        path: 'dashboard',
        loadChildren: () => import('../dashboard/dashboard.module').then( m => m.DashboardPageModule)

      },
      {
        path: 'eye',
        loadChildren: () => import('../rutina/rutina.module').then( m => m.RutinaPageModule)

      },
      {
        path: 'nutrition',
        loadChildren: () => import('../comida/comida.module').then( m => m.ComidaPageModule)
      },
      {
        path: 'about',
        loadChildren: () => import('../perfil/perfil.module').then( m => m.PerfilPageModule)
      }

    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TabsPageRoutingModule {}
