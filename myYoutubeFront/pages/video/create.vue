<template>
<div>
  <v-file-input show-size label="File input" v-model="source"></v-file-input>
  <v-text-field label="Title" :rules="rules" hide-details="auto" v-model="name"></v-text-field>
  <br><br>
    <v-btn block color="primary" dark @click="upload">Upload</v-btn>
</div>
</template>

<script>
  export default {

  data: () => ({
      rules: [
        value => !!value || 'Required.',
        value => (value && value.length >= 3) || 'Min 3 characters',
      ],
    }),
    
    methods: {
      upload() {
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('source', this.source);
        //console.log(localStorage.token)
        this.$axios.post('http://127.0.0.1:8000/api/video', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': 'Bearer '+localStorage.token,
            }
        }).then( response => console.log(response),
        setTimeout(() => {this.$router.push("/video/list")}, 1000)
        )
    },
    }

  }
</script>