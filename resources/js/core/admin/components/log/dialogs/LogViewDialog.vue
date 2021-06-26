<template>
  <div>
    <template v-if="!isVisible">
      <v-tooltip top>
        <template #activator="{ on, attrs }">
          <v-btn
            dark
            @click="handleViewLog"
            v-bind="attrs"
            v-on="on"
            x-small
            icon
          >
            <v-icon>mdi-clock</v-icon>
          </v-btn>
        </template>
        <span>View log</span>
      </v-tooltip>
    </template>
    <template v-else>
      <v-row justify="center">
        <v-dialog
          :value="true"
          persistent
          max-width="800px"
        >
          <v-card>
            <v-toolbar
              color="primary"
              dark
              flat
              dense
            >
              <v-toolbar-title>{{ title }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn
                icon
                dark
                small
                @click="handleCloseDialog"
              >
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
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
              />
          </v-card>
        </v-dialog>
      </v-row>
    </template>
  </div>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from '../../../../base/components/ui/tables/AppTable';

  export default {
    name: 'LogViewDialog',
    components: { 
      AppTable, 
    },
    props: {
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
        title: 'Permission Log',
        isVisible: false,
        table: {
          title: 'Log',
          headers: [
            { text: 'Date', value: 'created_at' },
            { text: 'Description', value: 'description' },
            { text: 'Properties', value: 'changes' },
            { text: 'User', value: 'causer' },
          ],
          orderBy: 'date',
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
          this.fetchLogData();
        },
        deep: true,
      },
    },
    methods: {
      ...mapActions('admin.log', ['getLog']),
      ...mapMutations('admin.log', ['setLog']),
      /**
       * Fetch log data
       */
      fetchLogData(){
        if(this.logData.subject_id){
          this.getLog(this.logData);
        }
      },
      /**
       * Triggered when view log button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleViewLog(){
        this.isVisible = true;
      },
       /**
       * Close dialog
       * 
       * @event click
       * @type {event}
       */
      handleCloseDialog(){
        this.isVisible = false;
      },
    },
    created(){
      this.setLog(null);
    },
    mounted(){
      this.fetchLogData();
    },
  }
</script>

<style scoped>
</style>