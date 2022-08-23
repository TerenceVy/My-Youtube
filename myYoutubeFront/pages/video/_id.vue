<template>
<div>
  <span>Title</span>
  <v-text-field label="Title" hide-details="auto" v-model="name"></v-text-field>
  <br><br>
  <span>User</span>
  <v-text-field label="User" hide-details="auto" v-model="user"></v-text-field>
  <br><br>
    <v-btn block color="primary" dark @click="update">Update</v-btn>
</div>
</template>

<script>
  export default {

    methods:{
    async update() {
      const formData = new FormData();
      if(this.name)
        {
          formData.append('name', this.name);
        }
        if(this.user)
        {
          formData.append('user', this.user);
        }
        console.log(this.name,this.user)
        await this.$axios
          .post('http://127.0.0.1:8000/api/video/'+this.$route.params.id, formData, {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer '+localStorage.token,
            }
          })
          .then(response => (
          this.info = response,
          setTimeout(() => {this.$router.push("/video/mylist")}, 1000)
          ))
   },
  fetchOnServer: false,
  }

  }
</script>