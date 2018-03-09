<template>
  <v-app>
    <v-toolbar color="primary" dark fixed app>
      <v-toolbar-title>
        {{ pageTitle || $t('Laravel Vue Spa Boilerplate') }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down">
        <v-btn flat v-for="navItem in navItems" :to="navItem.slug" :key="navItem.slug">{{ navItem.title }}</v-btn>
    </v-toolbar-items>
    <v-toolbar-side-icon class="hidden-md-and-up" @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    </v-toolbar>
     <v-navigation-drawer
      fixed
      v-model="drawer"
      right
      disable-resize-watcher
      floating
    >
      <v-list dense>

        <v-list-tile v-for="navItem in navItems" :to="navItem.slug" :key="navItem.slug">

          <v-list-tile-content>
            <v-list-tile-title>
            {{ navItem.title }}
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

      </v-list>
    </v-navigation-drawer>
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
        navItems: {},
        drawer: false
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
