<template>
  <v-container
    fluid
    class="page-content login-page fill-height"
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
        {{ $t('enterToSystem') }}
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
</template>
<script>
  export default {
    name: 'LoginPage'
  }
</script>
<style lang="scss">

</style>
