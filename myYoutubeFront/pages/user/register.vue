<template>
    <form>
    <v-toolbar-title>Register </v-toolbar-title>
    <v-text-field
      v-model="name"
      :error-messages="nameErrors"
      :counter="10"
      label="Name"
      required
      @input="$v.name.$touch()"
      @blur="$v.name.$touch()"
    ></v-text-field>
    <v-text-field
      v-model="pseudo"
      :error-messages="pseudoErrors"
      :counter="10"
      label="Pseudo"
      @input="$v.pseudo.$touch()"
      @blur="$v.pseudo.$touch()"
    ></v-text-field>
    <v-text-field
      v-model="email"
      :error-messages="emailErrors"
      label="E-mail"
      required
      @input="$v.email.$touch()"
      @blur="$v.email.$touch()"
    ></v-text-field>
    <v-text-field
      v-model="password"
      :error-messages="passwordErrors"
      label="Password"
      required
      @input="$v.password.$touch()"
      @blur="$v.password.$touch()"
    ></v-text-field>
    <v-btn class="mr-4" @click="register">submit</v-btn>
    <v-btn @click="clear">clear</v-btn>
  </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required, maxLength, email } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],

    validations: {
      name: { required, maxLength: maxLength(10) },
      email: { required, email },
      password: { required },
      pseudo:{ maxLength: maxLength(10) }
    },

    props: {
      source: String,
    },

    data: () => ({
      name: '',
      email: '',
      password: '',
    }),

    computed: {
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long')
        !this.$v.name.required && errors.push('Name is required.')
        return errors
      },
      passwordErrors () {
        const errors = []
        if (!this.$v.password.$dirty) return errors
        !this.$v.password.required && errors.push('Password is required.')
        return errors
      },
      pseudoErrors () {
        const errors = []
        if (!this.$v.password.$dirty) return errors
        !this.$v.pseudo.maxLength && errors.push('Pseudo must be at most 10 characters long')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.email.$dirty) return errors
        !this.$v.email.email && errors.push('Must be valid e-mail')
        !this.$v.email.required && errors.push('E-mail is required')
        return errors
      },
    },

    methods: {
      submit () {
        this.$v.$touch()
      },
      clear () {
        this.$v.$reset()
        this.name = ''
        this.email = ''
        this.password = ''
        this.pseudo = ''
      },

      register() {
        const formData = new FormData();
        formData.append('email', this.email);
        formData.append('username', this.name);
        formData.append('password', this.password);
        formData.append('pseudo', this.pseudo);

        this.$axios
          .post('http://127.0.0.1:8000/api/user', formData)
          .then(response => (
          this.info = response,
          localStorage.token = response.data.data.token,
          localStorage.id = response.data.data.user.id
        ))
        setTimeout(() => {  if(localStorage.id != null && localStorage.token != null)
        {
          this.$router.push("/video/list")
        }}, 1000);
    },
    },
  }
</script>