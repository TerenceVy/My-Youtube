<template>
  <v-simple-table fixed-header height="500px">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-left">Name</th>
          <th class="text-left">Email</th>
        </tr>
      </thead>
      <p v-if="$fetchState.pending">Fetching users...</p>
      <p v-else-if="$fetchState.error">No users yet.</p>
    <ul v-else>
      <li v-for="user of users" :key="user.id">
        <tr>
        <n-link :to="`/posts/${user.id}`">{{user.username}}          </n-link>
        <n-link :to="`/posts/${user.id}`">{{user.email}}</n-link>
        </tr>
      </li>
    </ul>
    </template>
  </v-simple-table>
</template>

<script>
// IL FAUT INSERER LES DONNEES DANS "data" POUR QUE CELUI CI RENDER LE TABLEAU.
// SEUL FONCTIONNALITE POUR LISTER = REGARDER LES LOGS POUR VOIR AFFICHER LE NOM + EMAIL
// CLIQUER SUR GO POUR REFAIRE UNE REQUETE
// A FINIR POUR AFFICHER LA LISTE
  export default {
  data () {
      return {
        users: [
          {
          }
        ]
      }
  },

  async fetch() {
       await this.$axios
          .get('http://127.0.0.1:8000/api/users')
          .then(response => (
          this.info = response
          ))
          this.users = this.info.data.data
   },
  fetchOnServer: false,
  };
</script>