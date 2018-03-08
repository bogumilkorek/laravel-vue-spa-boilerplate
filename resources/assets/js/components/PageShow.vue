<template>
        <v-container>
   <v-layout
          column
          wrap
          align-center
        >

    <h2 class="headline">{{ page.title }}</h2>
    <p v-html="page.content"></p>
     <img v-for="image in page.images" :key="image.url" v-img:gallery :src="'photos/upload/' + image.url" class="img-responsive" />
         </v-layout>
        </v-container>

</template>

<script>
export default {

    data() {
      return {
     page: {}
   }
    },

   created() {
      axios
        .get("/api/pages/" + this.$route.params.slug)
        .then(response => {
          this.page = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
}
</script>

<style lang="scss" scoped>
  img {
    max-height: 200px;
  }
</style>
