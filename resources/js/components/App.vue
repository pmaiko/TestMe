<template>
  <v-app>
    <v-app-bar
      title="TestMe"
      icon="13123"
      color="red"
      scroll-behavior="elevate fade-image inverted"
      image="https://picsum.photos/1920/1080?random"
    >
      <v-app-bar-nav-icon />
    </v-app-bar>

    <!--<v-main>-->
    <!--  <router-view></router-view>-->
    <!--</v-main>-->
    <v-main>
      <v-container
        fluid
        class="fill-height"
      >
        <v-row
          align="center"
          justify="center"
          class="fill-height"
        >
          <v-col
            sm="8"
            md="3"
          >
            <v-form>
              <v-text-field label="Email" />
              <v-text-field label="Password" />
              
              <v-btn
                block
                color="green"
                size="x-large"
              >
                Sign in
              </v-btn>
            </v-form>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>
<script>
  export default {
    setup: () => ({
      title: 'How To Install Vue 3 in Laravel 8 From Scratch'
    })
  }
</script>
