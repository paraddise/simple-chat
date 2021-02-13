<template>
  <div>
    <h3>Update chat</h3>
    <form>
      <div class="form-group">
        <label for="titleInput">Title</label>
        <input id="titleInput" type="text" v-model="updateForm.title"/>
      </div>

      <div class="form-group">
        <label for="descInput">Description</label>
        <input id="descInput" type="text" v-model="updateForm.description"/>
      </div>

      select
    </form>
  </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'

export default {
  name: "UpdateChat",
  data() {
    return {
      updateForm: {
        title: '',
        description: '',
        users: []
      },
      errors: {}
    }
  },
  methods: {
    ...mapActions(['fetchChat']),
  },
  computed: {
    ...mapGetters(['chat'])
  },
  mounted() {
    if (this.$route.query.id) {
      this.fetchChat(this.$route.query.id).then(({data}) => {
        this.updateForm.title = data.title;
        this.updateForm.description = data.description
      })
    }
  }

}
</script>

<style scoped>

</style>