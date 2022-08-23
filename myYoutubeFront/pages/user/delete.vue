<template>
<div>
    <v-btn block color="primary" dark @click="deleteAccount">Delete my account</v-btn>
</div>
</template>

<script>
export default {
    
methods:{
    deleteAccount(id){
       this.$axios
          .delete('http://127.0.0.1:8000/api/user/'+localStorage.id,
          {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': 'Bearer '+localStorage.token,
            }}
            )
          .then(response => (
          this.info = response ,
          setTimeout(() => {this.$router.push("/user/register")}, 1000)
          ))
          this.videos = this.info.data.data
    }
  }

  }
</script>