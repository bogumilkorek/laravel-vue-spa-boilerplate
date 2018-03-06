<template>
<div>Hello</div>
</template>

<script>
export default {

   data: () => ({
   }),

       created() {
      this.$store.dispatch("setPageTitle", this.$t('Test'))
       }

}
</script>
