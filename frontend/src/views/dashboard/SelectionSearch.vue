<template>
  <v-toolbar
    dark
    color="teal"
  >
    <v-toolbar-title style="font-size:16px">{{ title }}: </v-toolbar-title>
    <v-autocomplete
      v-model="select"
      :loading="loading"
      :items="items"
      :search-input.sync="search"
      cache-items
      class="mx-4"
      flat
      hide-no-data
      hide-details
      label="Please type the search key..."
      solo-inverted
    ></v-autocomplete>
    <v-btn @click="removeFilter(type)" style="background-color: teal;">
      <v-icon>mdi-cancel</v-icon>
    </v-btn>
  </v-toolbar>
</template>

<script>
  export default {
    props: {
      type: String,
      title: String,
      states: Array,
      changeFilter: Function,
      clearFilter: Function
    },
    data () {
      return {
        loading: false,
        items: [],
        search: null,
        select: null,
      }
    },
    mounted() {
      console.log("Brands", this.states)
    },
    watch: {
      search (val) {
        val !== this.select && this.querySelections(val)
      },
      select (val) {
        console.log('select', this.type, val);
        this.changeFilter(this.type, val);
      }
    },
    methods: {
      querySelections (v) {
        this.loading = true
        // Simulated ajax query
        setTimeout(() => {
          this.items = this.states.filter(e => {
            return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
          })
          this.loading = false
        }, 500)
      },
      removeFilter(type) {
        this.select = '';
        this.clearFilter(type);
      }
    },
  }
</script>