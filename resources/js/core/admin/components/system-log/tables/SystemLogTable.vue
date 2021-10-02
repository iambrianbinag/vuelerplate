<template>
    <AppTable
      :title="table.title"
      :headers="table.headers"
      :data="log"
      :params="logData"
      :action="getLog"
      :mutation="setLog"
      :loading="isLoadingGetLog"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #created_at="{ item }">
        {{ $moment(item.created_at).format('YYYY-MM-DD hh:mm A') }}
      </template>
      <template #log_name="{ item }">
        {{ item.log_name | capitalize }}
      </template>
      <template #description="{ item }">
        {{ item.description | capitalize }}
      </template>
      <template #changes="{ item }">
        <v-chip
          color="primary"
          small
          dark
        >
          New: {{ item.changes.attributes }}
        </v-chip>
        <v-chip
          v-if="item.changes.old"
          color="secondary"
          small
          dark
        >
            Old: {{ item.changes.old }}
        </v-chip>
      </template>
      <template #causer="{ item }">
        <template v-if="item.causer">
          {{ item.causer.name | capitalize }}
        </template>
      </template>
    </AppTable>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from 'base/components/ui/tables/AppTable';

  export default {
    name: 'LogViewDialog',
    components: { 
      AppTable, 
    },
    props: {
      /**
       * Boolean whether to remove log_name column
       */
      isFilteredByLogName: {
        type: Boolean,
        default: false
      },
      /**
       * The log data
       */
      logData: {
        type: Object,
        default: function(){
          return {
            'log_name': null,
            'subject_id': null,
          };
        },
      },
    },
    data(){
      return {
        table: {
          title: 'System Log',
          headers: [
            { text: 'Date', value: 'created_at', },
            { text: 'Type', value: 'log_name', },
            { text: 'Description', value: 'description', },
            { text: 'Properties', value: 'changes', sortable: false, },
            { text: 'User', value: 'causer', sortable: false, },
          ],
          orderBy: 'created_at',
          orderDirection: 'desc'
        },
      }
    },
    computed: {
      ...mapGetters('admin.log', [
        'log',
        'isLoadingGetLog',
      ]),
    },
    watch: {
      logData: {
        handler(){
          this.getLog();
        },
        deep: true,
      },
      isFilteredByLogName: function(value){
        this.setWhetherToRemoveLogNameColumnInTable();
      },
    },
    methods: {
      ...mapActions('admin.log', ['getLog']),
      ...mapMutations('admin.log', ['setLog']),
      /**
       * Set whether to remove log_name column in table
       */
      setWhetherToRemoveLogNameColumnInTable(){
        if(this.isFilteredByLogName){
          this.table.headers = this.table.headers.filter(header => header.value !== 'log_name');
        }
      }
    },
    created(){
      this.setLog(null);
    },
    mounted(){
      this.setWhetherToRemoveLogNameColumnInTable();
      this.getLog();
    },
  }
</script>

<style scoped>
</style>