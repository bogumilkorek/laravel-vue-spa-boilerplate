<template>
  <v-app>
    <v-toolbar color="primary" dark fixed app>
      <v-toolbar-title>
        {{ pageTitle || $t('Laravel Vue Spa Boilerplate') }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
    <v-toolbar-items>
        <v-btn flat v-for="navItem in navItems" :to="navItem.slug" :key="navItem.slug">{{ navItem.title }}</v-btn>
    </v-toolbar-items>
    </v-toolbar>
    <v-content>
      <v-container fluid>
                          
        <transition name="fade">
			    <router-view :key="$route.fullPath"></router-view>
        </transition>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mapGetters } from "vuex"
export default {

      data() {
      return {
        navItems: {}
    }
      },

    computed: mapGetters(["isLogged", "pageTitle"]),

    created() {
      axios
        .get("/api/pages/get-nav")
        .then(response => {
          this.navItems = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
};
</script>
