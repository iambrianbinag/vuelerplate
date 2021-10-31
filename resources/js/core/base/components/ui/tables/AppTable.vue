<template>
  <v-card>
    <v-card-title>
      <v-row no-gutters>
        <v-col
          cols="12"
          sm="4"
        >
          <AppHeader 
            :title="title"
            :backButton="backButton" 
            :elevation="false" 
          />
        </v-col>
        <v-spacer></v-spacer>
        <v-col
          cols="12"
          sm="4"
        >
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            dense
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      :options.sync="options"
      :loading="loading"
      :footer-props="footerProps"
      :search="search"
      dense
    >
      <template v-for="header in headers" #[`item.${header.value}`]="{ item }">
        <slot :name="header.value" :item="item" >{{ item[header.value] }}</slot>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
  import AppHeader from '../headers/AppHeader';

  export default {
    name: 'TableServer',
    components: { AppHeader },
    props: {
      /**
       * The title of table header
       */
      title: {
        type: String,
        default: '',
      },
      /**
       * Boolean whether to add back button
       */
      backButton: {
        type: Boolean,
        default: false,
      },
      /**
       * The header columns of the table
       */
      headers: {
        type: Array,
        default: [],
      },
      /**
       * The items of the table
       */
      data: {
        type: Array,
        default: function(){
          return [];
        },
      },
      /**
       * The loading state of the table
       */
      loading: {
        type: Boolean,
        default: false,
      },
      /**
       * The column to be ordered by, the value should match with headers props
       */
      orderByDefault: {
        type: String,
        default: null,
      },
      /**
       * The order direction of ordered column
       * `asc, desc`
       */
      orderDirectionDefault: {
        validator: function(value){
          return ['asc', 'desc'].includes(value);
        },
      },
    },
    data () {
      return {
        search: '',
        items: [],
        options: {
          groupBy: [],
          groupDesc: [],
          itemsPerPage: 10,
          multiSort: false,
          mustSort: false,
          page: 1,
          sortBy: this.orderByDefault ? [this.orderByDefault] : [],
          sortDesc: this.orderByDefault ? 
            [this.orderDirectionDefault === 'asc' ? false : true] : [],
        },
        footerProps: {
          showFirstLastPage: true,
          'items-per-page-options': [10, 20, 30, 40, 50],
        },
      }
    },
    watch: {
      data: {
        handler(value){
          this.setData(value);
        },
        deep: true,
      },
    },
    methods: {
      /**
       * Format data items
       * @param {array} array to be formatted
       */
      setData(items){
        if(Array.isArray(items)){
          this.items = items;
        }
      },
    },
    mounted(){
      this.setData(this.data);
    },
  }
</script>

<style scoped>
</style>