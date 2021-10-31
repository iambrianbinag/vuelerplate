<template>
  <v-container>
    <div class="mb-3">
        <v-btn
          small
          color="primary"
          @click="handleCreateSetting"
        >
          <v-icon left>
            mdi-plus
          </v-icon>
          Add
        </v-btn>
    </div>
    <AppTable
      :title="table.title"
      :headers="table.headers"
      :data="settings"
      :loading="isLoadingGetSettings"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #action="{ item }">
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="secondary"
              @click="handleSettingView(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </template>
          <span>View setting</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="primary"
              @click="handleSettingUpdate(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          <span>Update setting</span>
        </v-tooltip>
      </template>
    </AppTable>
    <template v-if="action.isVisible">
      <SettingViewDialog
        v-if="action.type == 'VIEW'"
        :visible.sync="action.isVisible"
        :setting="action.setting"
      />
      <SettingFormDialog 
        v-else
        :visible.sync="action.isVisible" 
        :setting="action.setting"
      />
    </template>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import AppTable from 'base/components/ui/tables/AppTable';
  import SettingFormDialog from './dialogs/SettingFormDialog';
  import SettingViewDialog from './dialogs/SettingViewDialog';

  export default {
    name: 'AppSettings',
    components: { 
      AppTable, 
      SettingFormDialog, 
      SettingViewDialog,  
    },
    data(){
      return {
        action: {
          type: null, // ADD, UPDATE, or VIEW
          isVisible: false,
          setting: null,
        },
        table: {
          title: 'Settings',
          headers: [
            { text: 'Name', value: 'name' },
            { text: 'Value', value: 'value' },
            {
              text: 'Action',
              align: 'start',
              sortable: false,
              value: 'action',
            },
          ],
          orderBy: null,
          orderDirection: null,
        },
      }
    },
    computed: {
      ...mapGetters('admin.settings', [
        'settings',
        'isLoadingGetSettings',
      ]),
    },
    watch: {
      'action.isVisible': function(value){
        if(!value){
          this.action = {
            type: null,
            isVisible: false,
            setting: null,
          }
        }
      },
    },
    methods: {
      ...mapActions('admin.settings', ['getSettings']),
      /**
       *  Triggered when create button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleCreateSetting(){
        this.action = {
          type: 'ADD',
          isVisible: true,
          setting: null,
        }
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleSettingView(setting){
        this.action = {
          type: 'VIEW',
          isVisible: true,
          setting,
        }
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleSettingUpdate(setting){
        this.action = {
          type: 'UPDATE',
          isVisible: true,
          setting,
        }
      },
    },
    mounted(){
      this.getSettings();
    }
  }
</script>

<style scoped>
</style>