<template>
  <v-container>
    <v-layout
      column
      wrap
      align-center
    >
      <h2 class="headline">{{ page.title }}</h2>
      <p v-html="page.content"></p>
        <img 
          v-for="image in imagesFromDropzone" 
          :key="image.url" 
          v-img:gallery :src="'photos/upload/' + image.url" 
          class="img-responsive" 
        />
    </v-layout>
  </v-container>
</template>

<script>
import router from '../router'

export default {
  data() {
    return {
      page: {
        images: []
      }
    };
  },

 computed: {
     imagesFromDropzone: function() {
       return this.page.images.filter(function(u) {
         return u.dropzone
     })
   }
 },

  created() {
    fetch("/api/pages/" + this.$route.params.slug)
      .then(response => {
        if(response.ok) {
           return response.json()
        }
        else {
           router.push({ name: 'notFound', params: { '0': this.$route.params.slug } })
        }
      })
      .then(response => {
        this.page = response
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
