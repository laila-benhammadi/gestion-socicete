import { createRouter, createWebHistory } from 'vue-router';

// Pages principales
import Login from '../Login.vue';
import Dashboard from '../Views/Admin/Employee/Dashboard.vue';
import ListEmployee from '../Views/Admin/Employee/ListEmployee.vue';
import AddEmployee from '../Views/Admin/Employee/AddEmployee.vue';
import Layouts from '../layouts/Layouts.vue';

// Détails et navigation employé
import DetailsEmployee from '@/Views/Admin/Employee/DetailsEmployee.vue';
import DetailsNav from '@/Views/Admin/Employee/DetailsNav.vue';

// Expériences
import ListExperience from '@/Views/Admin/Employee/ListExperience.vue';
import AddExperience from '@/Views/Admin/Employee/AddExperience.vue';
import EditExperience from '@/Views/Admin/Employee/EditExperience.vue';

// Banques
import ListBank from '@/Views/Admin/Employee/ListBank.vue';

// Congés
import HolidayList from '@/Views/Admin/Employee/HolidayList.vue';
import AddHoliday from '@/Views/Admin/Employee/AddHoliday.vue';
import UpdateHoliday from '@/Views/Admin/Employee/UpdateHoliday.vue';

// Salaires
import SalaryList from '@/Views/Admin/Employee/SalaryList.vue';
import AddSalary from '@/Views/Admin/Employee/AddSalary.vue';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/app',
    component: Layouts,
    meta: { requiresAuth: true },
    children: [
      {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
      },
      {
        path: '/listEmployee',
        name: 'ListEmployee',
        component: ListEmployee,
      },
      {
        path: '/AddEmployee',
        name: 'AddEmployee',
        component: AddEmployee,
      },
      {
        path: '/employee/:id/nav',
        component: DetailsNav,
        children: [
          {
            path: '',
            name: 'DetailsNav',
            component: DetailsEmployee,
          },
          {
            path: 'experience',
            name: 'ListExperience',
            component: ListExperience,
          },
          {
            path: 'bank',
            name: 'ListBank',
            component: ListBank,
          },
          {
            path: 'holiday',
            name: 'holidayList',
            component: HolidayList,
          },
          {
            path: 'addHoliday',
            name: 'addHoliday',
            component: AddHoliday,
          },
          {
            path: 'addexperience',
            name: 'addexperience',
            component: AddExperience,
          },
          {
            path: '/employee/:id/nav/editExperience/:experienceId',
            name: 'editExperience',
            component: EditExperience,
          },
          {
            path: '/employee/:id/nav/updateHoliday/:holidayId',
            name: 'updateHoliday',
            component: UpdateHoliday,
            props: true,
          },
          {
            path: '/employee/:id/salaries',
            name: 'SalaryList',
            component: SalaryList,
          },
          {
            path: '/employee/:id/experience/:experienceId/addSalary',
            name: 'addSalary',
            component: AddSalary,
          },
        ],
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Gestion de l’authentification
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
    next({ name: 'Dashboard' });
  } else {
    next();
  }
});

export default router;
