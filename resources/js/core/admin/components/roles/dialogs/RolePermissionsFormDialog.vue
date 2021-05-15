<template>
  <v-row
    justify="center"
  >
    <v-dialog
      v-model="visible"
      persistent
      max-width="700px"
    >
      <LoadingDialog v-if="isLoadingGetRolePermissions" />
      <v-card v-else>
        <v-toolbar
          color="primary"
          dark
          flat
          dense
        >
          <v-toolbar-title>Role's permissions</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-row>
            <v-col
              cols="12"
              md="6"
            >
              <v-treeview
                v-model="tree"
                :items="items"
                selected-color="primary"
                selectable
                return-object
                dense
              />
            </v-col>
            <v-spacer class="hidden-sm-and-down">
              <v-divider vertical />
            </v-spacer>
            <v-col
              class="hidden-md-and-up"
              cols="12"
            >
              <v-divider/>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <div
                v-if="tree.length === 0"
                class="font-weight-light grey--text text-center mt-md-2"
              >
                Select permissions
              </div>
              <v-scroll-x-transition
                group
                hide-on-leave
              >
                <v-chip
                  v-for="(selection, i) in tree"
                  :key="i"
                  color="grey"
                  dark
                  small
                  class="ma-1"
                >
                  <v-icon
                    left
                    small
                  >
                    mdi-account-check
                  </v-icon>
                  {{ selection.name }}
                </v-chip>
              </v-scroll-x-transition>
            </v-col>
            <v-col
              cols="12"
            >
              <v-divider></v-divider>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color='secondary'
            @click="closeDialog"
            small
          >
              Cancel
          </v-btn>
          <v-btn
            :loading="false"
            color='primary'
            type='submit'
            small
          >
              Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import LoadingDialog from '../../../../base/components/ui/loading/LoadingDialog';

  export default {
    name: 'RolePermissionsFormDialog',
    components: {
      LoadingDialog,
    },
    props: {
      /**
       * The role details
       */
      role: {
        type: Object,
        default: null,
      },
      /**
       * Visible status of dialog
       */
      visible: {
        type: Boolean,
        default: false,
      },
      /**
       * Get called when action is successful
       */
      successCallback: {
        type: Function,
        default: () => {},
      }
    },
    data(){
      return {
        breweries: [],
        isLoading: false,
        tree: [],
        types: [],



        title: "Role's permissions",
        form: {
          role: null,
          permissions: [],
        }
      }
    },
    computed: {
      items () {
        return [
          { id: 1, name: 'Permission 1' },
          { id: 2, name: 'Permission 2' },
          { id: 3, name: 'Permission 3' },
          { id: 4, name: 'Permission 4' },
          { id: 5, name: 'Permission 5' },
          { id: 11, name: 'Permission 11' },
          { id: 22, name: 'Permission 22' },
          { id: 33, name: 'Permission 33' },
          { id: 44, name: 'Permission 44' },
          { id: 55, name: 'Permission 55' },
          { id: 111, name: 'Permission 111' },
          { id: 222, name: 'Permission 222' },
          { id: 333, name: 'Permission 333' },
          { id: 444, name: 'Permission 444' },
          { id: 555, name: 'Permission 555' },
        ]
      },


      ...mapGetters('admin.roles', [
        'isLoadingGetRolePermissions',
      ]),
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.roles', [
        'getRolePermissions',
      ]),
      /**
       * Close dialog
       */
      closeDialog(){
        this.$emit('update:visible', false);
      },
      /**
       * Get role's permissions
       */
      fetchRolePermissions(){
        if(this.role.id){
          this.getRolePermissions({ id: this.role.id });
        }
      },
      /**
       *  Triggered when form is submitted
       * 
       * @event click
       * @type {event}
       */
      handleFormSubmit(){
        this.$v.$touch();
        if(this.$v.$invalid){
          return;
        }

        const params = {...this.form};

        this.updateRole(params)
          .then((response) => {
            this.showSnackbar({
              message: 'Role updated successfully'
            });
            this.$v.$reset();
            this.successCallback();
            this.closeDialog();
          });
      },
    },
    mounted(){
      this.fetchRolePermissions();
    }
  }
</script>

<style scoped>
</style>