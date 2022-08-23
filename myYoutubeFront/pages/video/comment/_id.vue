<template>
<div>
  <span>Your comment</span>
  <v-text-field label="Comment" hide-details="auto" v-model="body"></v-text-field>
  <br><br>
    <v-btn block color="primary" @click="addComment" dark>Post it</v-btn>
</div>
</template>

<script>
  export default {

  methods:{
    addComment() {
      const formData = new FormData();
        formData.append('body', this.body);

       this.$axios
          .post('http://127.0.0.1:8000/api/video/'+this.$route.params.id+'/comments',formData,{
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': 'Bearer '+localStorage.token,
            }
          })
          .then(response => (
          this.info = response,
          setTimeout(() => {this.$router.push("/video/comment/lists/"+this.$route.params.id)}, 1000)
          ))
          this.users = this.info.data.data
   },
  fetchOnServer: false,
  }
}
</script>