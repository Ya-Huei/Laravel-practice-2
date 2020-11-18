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
            // {
            //   path: ':id/edit',
            //   meta: { label: 'Edit Role' },
            //   name: 'Edit Role',
            //   component: EditRole
            // },
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
