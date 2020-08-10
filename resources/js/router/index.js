import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home'
import Create from "../views/Create";
import Test from "../views/Test";
import Result from "../views/Result";

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/create',
        name: 'Create',
        component: Create
    },
    {
        path: '/test/:vkid',
        name: 'Test',
        component: Test
    },
    {
        path: '/results/:id',
        name: 'Result',
        component: Result
    },
    {
        path: '/*',
        redirect: to => {
            if (/^\/[0-9]+/.test(to.fullPath)) {
                return '/test' + to.fullPath
            }
            return '/';
        }
    }
]

const router = new VueRouter({
    routes
})

export default router
