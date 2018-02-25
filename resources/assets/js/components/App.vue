<template>
  <v-app>
    <v-navigation-drawer
      fixed
      v-model="drawer"
      app
    >
      <v-list dense v-if="isLogged">

        <v-list-tile :to="{ name: 'pages' }">
          <v-list-tile-action>
            <v-icon>layers</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              {{ $t('Pages') }}
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile @click="logout">
          <v-list-tile-action>
            <v-icon>highlight_off</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              {{ $t('Logout') }}
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>

      <v-list dense v-else>
           <v-list-tile :to="{ name: 'login' }">
          <v-list-tile-action>
            <v-icon>face</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              {{ $t('Login') }}
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
     </v-list>

    </v-navigation-drawer>
    <v-toolbar color="primary" dark fixed app>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title id="page-title">
        {{ pageTitle || $t('Admin panel') }}
      </v-toolbar-title>
    </v-toolbar>
    <v-content>
      <v-container fluid>
        <transition name="fade">
			    <router-view></router-view>
        </transition>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import { mapGetters } from "vuex"
export default {
  data: () => ({
    drawer: null
  }),
  name: "app",

  computed: mapGetters(["isLogged", "pageTitle"]),

  methods: {
    logout() {
      this.$store.dispatch("logout")
    }
  }
};
</script>
