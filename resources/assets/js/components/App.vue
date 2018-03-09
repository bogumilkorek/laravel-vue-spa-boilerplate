<template>
    <div> 
        <admin v-if="(isLogged && $route.meta.requiresAuth == true) || $route.name == 'login'"></admin>
        <home v-else></home>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Admin from './admin/Admin.vue'
    import Home from './Home.vue'
    export default {
    
        components: {
            'admin': Admin,
            'home': Home
        },

        computed: mapGetters(['isLogged', 'pageTitle']),
    }
</script>
