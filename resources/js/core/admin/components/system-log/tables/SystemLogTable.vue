<template>
    <TableServer
      :title="table.title"
      :headers="table.headers"
      :data="systemLog"
      :params="systemLogData"
      :action="getSystemLog"
      :mutation="setSystemLog"
      :loading="isLoadingGetSystemLog"
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
        <SystemLogChanges :propertiesData="item.changes" />
      </template>
      <template #causer="{ item }">
        <template v-if="item.causer">
          {{ item.causer.name | capitalize }}
        </template>
      </template>
    </TableServer>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import TableServer from 'base/components/ui/tables/TableServer';
  import SystemLogChanges from '../lists/SystemLogChanges';

  export default {
    name: 'SystemLogTable',
    components: { 
      TableServer,
      SystemLogChanges, 
    },
    props: {
      /**
       * Boolean whether to remove log_name column
       */
      isFilteredBySystemLogName: {
        type: Boolean,
        default: false
      },
      /**
       * The log data
       */
      systemLogData: {
        type: Object,
        default: function(){
          return {
            'log_name': '',
            'subject_id': '',
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
      ...mapGetters('admin.system-log', [
        'systemLog',
        'isLoadingGetSystemLog',
      ]),
    },
    watch: {
      systemLogData: {
        handler(){
          this.fetchSystemLogData();
        },
        deep: true,
      },
      isFilteredBySystemLogName: function(value){
        this.setWhetherToRemoveLogNameColumnInTable();
      },
    },
    methods: {
      ...mapActions('admin.system-log', ['getSystemLog']),
      ...mapMutations('admin.system-log', ['setSystemLog']),
      /**
       * Fetch log data
       */
      fetchSystemLogData(){
        this.getSystemLog(this.systemLogData);
      },
      /**
       * Set whether to remove log_name column in table
       */
      setWhetherToRemoveLogNameColumnInTable(){
        if(this.isFilteredBySystemLogName){
          this.table.headers = this.table.headers.filter(header => header.value !== 'log_name');
        }
      }
    },
    created(){
      this.setSystemLog(null);
    },
    mounted(){
      this.setWhetherToRemoveLogNameColumnInTable();
      this.fetchSystemLogData();
    },
  }
</script>

<style scoped>
</style>