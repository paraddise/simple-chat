import {createRouter, createWebHistory} from 'vue-router';
import ChatList from "@/components/ChatList";
import Login from "@/views/Login";
import Signup from "@/views/SignUp";
import authService from "@/services/auth.service";
import CreateChat from "@/views/CreateChat";
import Chats from "@/views/Chats";
import UpdateChat from "@/views/UpdateChat";

const routes = [
    {
        path: '/',
        name: 'home',
        redirect: '/chats',
        meta: {
            authRequired: true
        }
    },
    {
        path: '/auth/login',
        name: 'login',
        components: {
            default: Login
        },
        meta: {
            onlyGuest: true,
        }
    },
    {
        path: '/auth/sign-up',
        name: 'signup',
        component: Signup,
        meta: {
            onlyGuest: true,
        }
    },
    {
        path: '/chats',
        name: 'Chats',
        components: {
            default: Chats,
            sidebar: ChatList,
        },
        meta: {
            authRequired: true
        },

    },
    {
        path: '/update-chat',
        name: 'chat-update',
        components: {
            default: UpdateChat,
            sidebar: ChatList
        }
    },
    {
        path: '/create-chat',
        name: '/chat-create',
        components: {
            default: CreateChat,
            sidebar: ChatList
        }
    }
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

router.beforeEach((to, from, next) => {
    /** If user already logged in */
    if (to.meta.onlyGuest && authService.isLoggedIn()) {
        next({name: 'home'})
    } else if (to.meta.authRequired && !authService.isLoggedIn()) {
        next({name: 'login'})
    } else {
        next()
    }
})

export default router
