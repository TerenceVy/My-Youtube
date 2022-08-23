<template>
  <v-simple-table fixed-header height="500px">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">Comment</th>
          <th class="text-left">User_id</th>
        </tr>
      </thead>
      <p v-if="$fetchState.pending">Fetching Comments...</p>
      <p v-else-if="$fetchState.error">No comment yet on this video</p>
    <ul v-else>
      <li v-for="comment of comments" :key="comment.id">
        <tr> 
        <n-link :to="`/posts/${comment.user_id}`">{{comment.body}}          </n-link>
        <n-link :to="`/posts/${comment.user_id}`">{{comment.user_id}}</n-link>
        </tr>
      </li>
    </ul>
    </template>
  </v-simple-table>
</template>

<script>
  export default {
    data () {
      return {
        comments: [
          {
          }
        ]
      }
  },

  async fetch() {

       await this.$axios
       .get('http://127.0.0.1:8000/api/video/'+this.$route.params.id+'/comments',{
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': 'Bearer '+localStorage.token,
            }
          })
          .then(response => (
          this.info = response,
          console.log(response)
          ))
          this.comments = this.info.data.data
          console.log(this.comments)
   },
  fetchOnServer: false,

  }
</script>