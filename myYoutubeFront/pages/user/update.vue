<template>
    <form>
    <v-toolbar-title>Update </v-toolbar-title>
    <v-text-field
      v-model="name"
      :error-messages="nameErrors"
      :counter="10"
      label="Name"
      name="name"
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
      @input="$v.email.$touch()"
      @blur="$v.email.$touch()"
    ></v-text-field>
    <v-text-field
      v-model="password"
      :error-messages="passwordErrors"
      label="Password"
      @input="$v.password.$touch()"
      @blur="$v.password.$touch()"
    ></v-text-field>
    <v-btn class="mr-4" @click="update">submit</v-btn>
    <v-btn @click="clear">clear</v-btn>
  </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required, maxLength, email } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin],

    validations: {
      name: { maxLength: maxLength(10) },
      email: { email },
      password: { },
      pseudo:{ maxLength: maxLength(10) }
    },

    props: {
      source: String,
    },

    data: () => ({
      name: '',
      email: '',
      password: '',
      pseudo: '',
    }),

    computed: {
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long')
        return errors
      },
      passwordErrors () {
        const errors = []
        if (!this.$v.password.$dirty) return errors
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

      update() {
        const formData = new FormData();
        if(this.email)
        {
          formData.append('email', this.email);
        }
        if(this.name)
        {
          formData.append('username', this.name);
        }
        if(this.password)
        {
          formData.append('password', this.password);
        }
        if(this.pseudo)
        {
          formData.append('pseudo', this.pseudo);
        }

        this.$axios.post('http://127.0.0.1:8000/api/user/'+localStorage.id, formData, {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+localStorage.token,
            }
        })
          .then(response => (
          this.info = response,
          console.log(response)
        ))
    },
    fetchOnServer: false
    }
  }
</script>