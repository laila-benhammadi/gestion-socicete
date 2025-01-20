import { createRouter, createWebHistory } from 'vue-router';
import Login from '../Login.vue'; 
import Dashboard from '../Views/Admin/Employee/Dashboard.vue';  
import ListEmployee from '../Views/Admin/Employee/ListEmployee.vue';
import AddEmployee from '../Views/Admin/Employee/AddEmployee.vue' ;
import Layouts from '../layouts/Layouts.vue';   
  import DetailsEmployee from '@/Views/Admin/Employee/DetailsEmployee.vue';
import ListExperience from '@/Views/Admin/Employee/ListExperience.vue';
import DetailsNav from '@/Views/Admin/Employee/DetailsNav.vue';
import ListBank from '@/Views/Admin/Employee/ListBank.vue';
import AddExperience from '@/Views/Admin/Employee/AddExperience.vue';
import HolidayList from '@/Views/Admin/Employee/HolidayList.vue';
import AddHoliday from '@/Views/Admin/Employee/AddHoliday.vue';
import EditExperience from '@/Views/Admin/Employee/EditExperience.vue';
import UpdateHoliday from '@/Views/Admin/Employee/UpdateHoliday.vue';
import SalaryList from '@/Views/Admin/Employee/SalaryList.vue';
import AddSalary from '@/Views/Admin/Employee/AddSalary.vue';
 

 
 const routes = [
  {
    path: '/login',
    name: 'Login',  
    component: Login,
  },
 
  {
    path: '/app' ,
    component: Layouts,
    meta: { requiresAuth: true },   
    children: [
      {
        path: '/',  
        name: 'Dashboard',
        component: Dashboard,  
      },
      {
        path:'/listEmployee',
        name:'ListEmployee',
        component:ListEmployee,
      },
      {
        path:'/AddEmployee',
        name: 'AddEmployee',
        component: AddEmployee, 
      },
       {
          path: '/employee/:id/nav',
          name: 'DetailsNav',
          component: DetailsNav,
          children: [
            {
            path: '',  
            component: DetailsEmployee,
          },
           {
              path: 'experience', 
              name : 'ListExperience',
              component: ListExperience,
            },
            {
              path: 'bank',
              name: 'ListBank',
              component: ListBank,  
            },{
              path: 'holiday',
              name: 'holidayList',
              component: HolidayList,  
            },{
              path: 'addHoliday',
              name: 'addHoliday',
              component: AddHoliday,  
            },{
              path: 'addexperience',
              name: 'addexperience',
              component: AddExperience,  
            },{
              path: '/employee/:employeeId/nav/editExperience/:experienceId',
              name: 'editExperience',
              component: EditExperience,
            },{
              path: '/employee/:employeeId/nav/updateHoliday/:holidayId',
              name: 'updateHoliday',
              component: UpdateHoliday, 
               props: true,
            },{
              path: '/employee/:employeeId/salaries',
              name: 'SalaryList',
             component: SalaryList,
            },
            {
              path: '/employee/:employeeId/experience/:experienceId/addSalary',
              name: 'addSalary',
              component: AddSalary,  
            },
            ]
        },   
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});




router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('user-token');  

  if (to.matched.some(record => record.meta.requiresAuth)) {
     if (!token) {
      console.log("Non connecté. Redirection vers Login.");
      next({ name: 'Login' });   
    } else {
      console.log("Utilisateur connecté. Accès permis.");
      next();  
    }
  } else if (to.name === 'Login' && token) {
    
    // console.log("Déjà connecté. Redirection vers Dashboard.");
    next({ name: 'Dashboard' });  
  } else {
    next();   
  }
});





export default router;
