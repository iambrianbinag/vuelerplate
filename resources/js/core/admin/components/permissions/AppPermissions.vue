<template>
  <v-container>
    <div class="mb-3">
        <v-btn
          small
          color="primary"
          @click="handleCreatePermission"
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
      :data="permissions"
      :action="getPermissions"
      :mutation="setPermissions"
      :loading="isLoadingGetPermissions"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #action="{ item }">
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="secondary"
              @click="handlePermissionView(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </template>
          <span>View permission</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="primary"
              @click="handlePermissionUpdate(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          <span>Update permission</span>
        </v-tooltip>
      </template>
    </AppTable>
    <template v-if="action.isVisible">
      <PermissionViewDialog
        v-if="action.type == 'VIEW'"
        :visible.sync="action.isVisible"
        :permission="action.permission"
      />
      <PermissionFormDialog 
        v-else
        :visible.sync="action.isVisible" 
        :permission="action.permission"
        :successCallback="getPermissions"
      />
    </template>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from '../../../base/components/ui/tables/AppTable';
  import PermissionFormDialog from './dialogs/PermissionFormDialog';
  import PermissionViewDialog from './dialogs/PermissionViewDialog';

  export default {
    name: 'AppRoles',
    components: { 
      AppTable, 
      PermissionFormDialog, 
      PermissionViewDialog,  
    },
    data(){
      return {
        action: {
          type: null, // ADD, UPDATE, or VIEW
          isVisible: false,
          permission: null,
        },
        table: {
          title: 'Permissions',
          headers: [
            { text: 'Name', value: 'name' },
            { text: 'Order', value: 'order' },
            {
              text: 'Action',
              align: 'start',
              sortable: false,
              value: 'action',
            },
          ],
          orderBy: 'order',
          orderDirection: 'desc'
        },
      }
    },
    computed: {
      ...mapGetters('admin.permissions', [
        'permissions',
        'isLoadingGetPermissions',
      ]),
    },
    watch: {
      'action.isVisible': function(value){
        if(!value){
          this.action = {
            type: null,
            isVisible: false,
            permission: null,
          }
        }
      },
    },
    methods: {
      ...mapActions('admin.permissions', ['getPermissions']),
      ...mapMutations('admin.permissions', ['setPermissions']),
      /**
       *  Triggered when create button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleCreatePermission(){
        this.action = {
          type: 'ADD',
          isVisible: true,
          permission: null,
        }
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handlePermissionView(permission){
        this.action = {
          type: 'VIEW',
          isVisible: true,
          permission,
        }
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handlePermissionUpdate(permission){
        this.action = {
          type: 'UPDATE',
          isVisible: true,
          permission,
        }
      },
    },
    created(){
      this.setPermissions(null);
    },
    mounted(){
      this.getPermissions();
    }
  }
</script>

<style scoped>
</style>