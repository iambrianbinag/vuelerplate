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
      <template v-for="header in headers" #[`item.${header.value}`]="{ item }">
        <slot :name="header.value" :item="item" >{{ item[header.value] }}</slot>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
  import AppHeader from '../headers/AppHeader';

  export default {
    name: 'AppTable',
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
       * The items of the table, the format should be paginated
       */
      data: {
        type: Object,
        default: function(){
          return {};
        },
      },
      /**
       * Gets called when internal table pagination behavior is changed
       */
      action: {
        type: Function,
        default: () => {},
      },
      /**
       * Gets called on destroy lifecycle, to set null on store's state
       */
      mutation: {
        type: Function,
        default: () => {},
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
          // Check if there has/have difference in new and old values, if any, then callthe  method that will request in backend
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
      /**
       * Format data to be matched in v-data-table component's properties
       * @param {object} object to be formatted
       */
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