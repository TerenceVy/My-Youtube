<template>
  <v-container
    class="fill-height"
    fluid
    style="min-height: 434px"
  >
   <v-fade-transition mode="out-in">
      <v-row>
      <tr>
      <p v-if="$fetchState.pending">Fetching videos...</p>
      <p v-else-if="$fetchState.error">No video for now.</p>
    <ul v-else>
      <li v-for="video of videos" :key="video.id">
          <v-col cols="12">
          <v-card>
            <v-img
              src="https://picsum.photos/350/165?random"
              max-height="125"
              contain
              class="grey darken-4"
            ></v-img>
            <v-card-title class="title"><n-link :to="`/video/details/${video.id}`">{{video.name}}</n-link>
            <n-link :to="`/video/details/${video.id}`"></n-link></v-card-title>
          </v-card>
        </v-col>
      </li>
    </ul>     
      </tr>
      </v-row>
    </v-fade-transition>
  </v-container>
</template>

<script>
  export default {
    data () {
      return {
        videos: [
          {
          }
        ]
      }
  },

    async fetch() {
        const formData = new FormData();
        formData.append("perPage", 20);

       await this.$axios
          .get('http://127.0.0.1:8000/api/videos', formData)
          .then(response => (
          this.info = response
          ))
          this.videos = this.info.data.data
   },
  fetchOnServer: false,
  }
</script>