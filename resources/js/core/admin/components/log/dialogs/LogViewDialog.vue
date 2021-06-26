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
            >
              <template #created_at="{ item }">
                {{ $moment(item.created_at).format('YYYY-MM-DD hh:mm A') }}
              </template>
              <template #description="{ item }">
                {{ item.description | capitalize }}
              </template>
              <template #changes="{ item }">
                <v-chip
                  color="primary"
                  dark
                >
                  New: {{ item.changes.attributes }}
                </v-chip>
                <v-chip
                  v-if="item.changes.old"
                  color="secondary"
                  dark
                >
                   Old: {{ item.changes.old }}
                </v-chip>
              </template>
              <template #causer="{ item }">
                {{ item.causer.name | capitalize }}
              </template>
            </AppTable>
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
      title: {
        type: String,
        required: true
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
        isVisible: false,
        table: {
          title: 'Log',
          headers: [
            { text: 'Date', value: 'created_at', },
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
        this.fetchLogData();
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