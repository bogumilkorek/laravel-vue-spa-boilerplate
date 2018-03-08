<!--
#openssl genrsa -passout pass:kh35634ihjjdsglkj346h -out storage/jwt/private.pem -aes256 4096
#openssl rsa -passin pass:kh35634ihjjdsglkj346h -pubout -in storage/jwt/private.pem -out public/jwt/public.pem
publish tymon

-->
<template>
  <v-layout align-center justify-center>
    <v-flex xs12 sm8 md4>
      <v-card class="elevation-12">
        <v-toolbar dark color="primary">
          <v-toolbar-title>
            <v-icon class="mr-2">account_circle</v-icon>{{ $t('Login') }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
          <v-alert
            type="error"
            :value="alert"
            transition="scale-transition"
          >
            <b>{{ message }}</b>
          </v-alert>

          <v-form v-model="valid" ref="form">
            <v-text-field 
              prepend-icon="person"
              name="login"
              :label="$t('Username')"
              type="text"
              v-model="credentials.login" 
              :rules="[
                        v => !!v || $t('Insert username'),
                        v => (v && v.length > 4) || $t('Insert at least 5 characters')
                      ]"
              required
            ></v-text-field>

            <v-text-field
              prepend-icon="lock"
              name="password"
              :label="$t('Password')" 
              :type="pmode ? 'text' : 'password'"
              v-model="credentials.password" 
              :rules="[
                        v => !!v || $t('Insert password'),
                        v => (v && v.length > 4) || $t('Insert at least 5 characters')
                      ]"
              :append-icon="pmode ? 'visibility' : 'visibility_off'"
              :append-icon-cb="() => (pmode = !pmode)"
              @keyup.enter.native="submit"
              required
            ></v-text-field>

          </v-form>
        </v-card-text>

        <v-card-actions class="text-xs-center">
          <v-spacer></v-spacer>
            <v-btn 
              color="primary"
              :disabled="!valid"
              :loading="loading"
              @click="submit"
            >
              <v-icon class="mr-2">check_circle</v-icon>{{ $t('Login') }}
            </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
    data() {
      return {
    loading: false,
    message: '',
    alert: false,
    valid: false,
    pmode: false,
    credentials: {
      login: '',
      password: ''
    }
  }
    },

  created() {
    this.$store.dispatch("setPageTitle", this.$t('Admin panel'))
  },

  methods: {
    submit() {
      this.loading = true
      this.alert = false
      this.valid = false
      if (this.$refs.form.validate()) {
        axios
          .post("/api/login", {
            email: this.credentials.login,
            password: this.credentials.password
          })
          .then(response => {
            sessionStorage.setItem("token", response.data.token)
            this.$store.dispatch("login")
          })
          .catch(error => {
            this.message = this.$t("Invalid data!")
            this.alert = true
            this.valid = true
            this.loading = false
          });
      }
    }
  }
};
</script>