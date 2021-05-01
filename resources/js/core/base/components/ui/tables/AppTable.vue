<template>
  <v-card>
    <v-card-title>
      <v-row no-gutters>
        <v-col
          cols="12"
          sm="4"
        >
          <AppHeader 
            title="Users" 
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
            @input="handleSearch"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      :options.sync="options"
      :server-items-length="total"
      :loading="loading"
      :footer-props="footerProps"
      dense
    >
    </v-data-table>
  </v-card>
</template>

<script>
  import AppHeader from '../headers/AppHeader';

  export default {
    name: 'AppTable',
    components: { AppHeader },
    props: {
      headers: {
        type: Array,
        default: [],
      },
      data: {
        type: Object,
        default: function(){
          return {};
        },
      },
      action: {
        type: Function,
        default: () => {},
      },
      mutation: {
        type: Function,
        default: () => {},
      },
      loading: {
        type: Boolean,
        default: false,
      },
      orderByDefault: {
        type: String,
        default: null,
      },
      orderDirectionDefault: {
        type: String,
        default: 'asc',
      },
    },
    data () {
      return {
        search: '',
        items: [],
        total: 0,
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
      options: {
        handler (newValue, oldValue) {
          let isMustRequestToBackend = false;

          if(newValue.page !== oldValue.page){
            isMustRequestToBackend = true;
          }
          if(newValue.itemsPerPage !== oldValue.itemsPerPage){
            isMustRequestToBackend = true;
          }
          if(newValue.sortBy.length !== oldValue.sortBy.length){
            isMustRequestToBackend = true;
          }
          if((newValue.sortBy.length && oldValue.sortBy.length) 
             && (newValue.sortBy[0] !== oldValue.sortBy[0])
          ){
            isMustRequestToBackend = true;
          }
          if(newValue.sortDesc.length !== oldValue.sortDesc.length){
            isMustRequestToBackend = true;
          }
          if((newValue.sortDesc.length && oldValue.sortDesc.length) 
             && (newValue.sortDesc[0] !== oldValue.sortDesc[0])
          ){
            isMustRequestToBackend = true;
          }

          if(isMustRequestToBackend){
            this.handleRequest();
          }
        },
        deep: true,
      },
    },
    methods: {
      setData(object = {}){
        if(!this.isObjectEmpty(object)){
          const { data, current_page, per_page, total } = object;
          this.items = data;
          this.total = total;
          this.options = {
            ...this.options,
            itemsPerPage: parseInt(per_page),
            page: parseInt(current_page),
          };
        }
      },
      /**
       * Triggered when search input's value is change
       * 
       * @event click
       * @type {event}
       */
      handleSearch(){
        this.handleRequest();
      },
      /**
       * Handle backend request
       */
      handleRequest(){
        const params = {};
        let { page, itemsPerPage, sortBy, sortDesc } = this.options;
        
        params.page = page;
        params.per_page = itemsPerPage;
        if(this.search){
          params.page = 1;
          params.search = this.search;
        }
        if(sortBy.length === 1){
          params.order_by = sortBy[0];
        } 
        if(sortBy.length === 1 && sortDesc.length === 1 && sortDesc[0] === true){
          params.order_direction = 'desc';
        }
        if(sortBy.length === 1 && sortDesc.length === 1 && sortDesc[0] === false){
          params.order_direction = 'asc';
        } 

        this.action(params);
      },
    },
    mounted() {
      this.setData(this.data);
    },
    destroyed(){
      this.mutation(null);
    },
  }
</script>

<style scoped>
</style>