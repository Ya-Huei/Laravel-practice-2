import Vue from 'vue'
import Router from 'vue-router'

// Containers
const TheContainer = () => import('@/containers/TheContainer')

// Views - Pages
const Page404 = () => import('@/views/pages/Page404')
const Page500 = () => import('@/views/pages/Page500')
const Login = () => import('@/views/pages/Login')

// Users
const Users = () => import('@/views/users/Users')
const EditUser = () => import('@/views/users/EditUser')
const CreateUser = () => import('@/views/users/CreateUser')

//Roles
const Roles = () => import('@/views/roles/Roles')
const EditRole = () => import('@/views/roles/EditRole')
const CreateRole = () => import('@/views/roles/CreateRole')

//Devices
const Devices = () => import('@/views/devices/Devices')
const EditDevice = () => import('@/views/devices/EditDevice')
const RepairDevice = () => import('@/views/devices/RepairDevice')

//Firmware
const Firmware = () => import('@/views/firmware/Firmware')

//OtaRecords
const Ota = () => import('@/views/ota/Ota')
const ShowOta = () => import('@/views/ota/ShowOta')
const UpdateOta = () => import('@/views/ota/UpdateOta')

//Recipes
const Recipes = () => import('@/views/recipes/Recipes')

//RepairRecords
const Repairs = () => import('@/views/repairs/Repairs')
const EditRepair = () => import('@/views/repairs/EditRepair')

Vue.use(Router)

export default new Router({
  mode: 'hash', // https://router.vuejs.org/api/#mode
  linkActiveClass: 'active',
  scrollBehavior: () => ({ y: 0 }),
  routes: configRoutes()
})

function configRoutes () {
  return [
    {
      path: '/',
      redirect: '/users',
      name: 'Home',
      component: TheContainer,
      children: [
        {
          path: 'users',
          name: 'Users',
          component: Users
        },
        {
          path: 'users',
          meta: { label: 'Users'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Users,
            },
            {
              path: 'create',
              meta: { label: 'Create User' },
              nmae: 'Create User',
              component: CreateUser
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit User' },
              name: 'Edit User',
              component: EditUser
            }
          ]
        },
        {
          path: 'roles',
          meta: { label: 'Roles'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Roles,
            },
            {
              path: 'create',
              meta: { label: 'Create Role' },
              name: 'Create Role',
              component: CreateRole
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Role' },
              name: 'Edit Role',
              component: EditRole
            },
          ]
        },
        {
          path: 'devices',
          meta: { label: 'Devices'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Devices,
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Device' },
              name: 'Edit Device',
              component: EditDevice
            },
            {
              path: ':id/repair',
              meta: { label: 'Repair Device' },
              name: 'Repair Device',
              component: RepairDevice
            },
          ]
        },
        {
          path: 'firmware',
          meta: { label: 'Firmware'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Firmware,
            },
            // {
            //   path: ':id/edit',
            //   meta: { label: 'Edit Role' },
            //   name: 'Edit Role',
            //   component: EditRole
            // },
          ]
        },
        {
          path: 'ota',
          meta: { label: 'Ota'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Ota,
            },
            {
              path: ':id/show',
              meta: { label: 'Show Ota' },
              name: 'Show Ota',
              component: ShowOta,
            },
            {
              path: 'update',
              meta: { label: 'Update Ota' },
              name: 'Update Ota',
              component: UpdateOta,
            },
          ]
        },
        {
          path: 'recipes',
          meta: { label: 'Recipes'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Recipes,
            },
            // {
            //   path: ':id/edit',
            //   meta: { label: 'Edit Role' },
            //   name: 'Edit Role',
            //   component: EditRole
            // },
          ]
        },
        {
          path: 'repairs',
          meta: { label: 'Repairs'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Repairs,
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Repair' },
              name: 'Edit Repair',
              component: EditRepair
            },
          ]
        },
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
      ]
    },
    {
      path: '/',
      redirect: '/login',
      name: 'Auth',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
      ]
    },
    {
      path: '*',
      name: '404',
      component: Page404
    }
  ]
}
