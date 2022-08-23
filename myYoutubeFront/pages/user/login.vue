<template>
      <v-container
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="12"
            md="12"
            lg="12"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="primary"
                dark
                flat
              >
                <v-toolbar-title>Login </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                </v-tooltip>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    v-model="email"
                    label="Email"
                    name="email"
                    prepend-icon="mdi-account"
                    type="text"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    v-model="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="login">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
</template>

<script>
  export default {
    props: {
      source: String,
    },

    data: () => ({
      email: '',
      password: '',
    }),

    methods: {
      login() {
        const formData = new FormData();
        formData.append('email', this.email);
        formData.append('password', this.password);

        this.$axios
          .post('http://127.0.0.1:8000/api/auth', formData)
          .then(response => (
          this.info = response,
          localStorage.token = response.data.token,
          localStorage.id = response.data.user.id
        ))
        setTimeout(() => {  if(localStorage.id != null && localStorage.token != null)
        {
          this.$router.push("/video/list")
        }}, 1000);
    
    },
    }
  }
</script>