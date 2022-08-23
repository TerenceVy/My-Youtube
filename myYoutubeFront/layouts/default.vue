<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar
      :clipped-left="clipped"
      fixed
      app
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-btn
        icon
        @click.stop="miniVariant = !miniVariant"
      >
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="clipped = !clipped"
      >
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn
        icon
        @click.stop="fixed = !fixed"
      >
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title" />
      <v-spacer />
      <v-btn
        icon
        @click.stop="rightDrawer = !rightDrawer"
      >
        <v-icon>mdi-menu</v-icon>
      </v-btn>
      <v-text-field

            single-line
            aria-placeholder="Search here..."
            v-model="search"
      ></v-text-field>    
        <v-btn class="mr-4" @click="search">Search</v-btn>
      
    </v-app-bar>
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <v-navigation-drawer
      v-model="rightDrawer"
      :right="right"
      temporary
      fixed
    >
    </v-navigation-drawer>
    <v-footer
      :absolute="!fixed"
      app
    >
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: 'Welcome',
          to: '/'
        },
        {
          icon: 'mdi-account-plus',
          title: 'Register',
          to: '/user/register'
        },
        {
          icon: 'mdi-account-arrow-left',
          title: 'Login',
          to: '/user/login'
        },
        {
          icon: 'mdi-account-edit',
          title: 'Update user',
          to: '/user/update'
        },
        {
          icon: 'mdi-account-group',
          title: 'List of users',
          to: '/user/list'
        },
        {
          icon: 'mdi-video-plus-outline',
          title: 'Create video',
          to: '/video/create'
        },
        {
          icon: 'mdi-video-account',
          title: 'My Video',
          to: '/video/mylist'
        },
        {
          icon: 'mdi-youtube',
          title: 'Video List',
          to: '/video/list'
        },
        {
          icon: 'mdi-video-minus-outline',
          title: 'Video Delete',
          to: '/video/delete'
        },
        {
          icon: 'mdi-comment-plus',
          title: 'Add comment',
          to: '/video/comment/choose'
        },
        {
          icon: 'mdi-comment-text-multiple-outline',
          title: 'List Comment',
          to: '/video/comment/chose'
        },
        {
          icon: 'mdi-account-cancel-outline',
          title: 'Delete User',
          to: '/user/delete'
        },
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: ''
    }
  },

  methods:
  {
    search(){
      setTimeout(() => {this.$router.push("/video/search/"+this.search)}, 1000)
    }
  }

}
</script>
