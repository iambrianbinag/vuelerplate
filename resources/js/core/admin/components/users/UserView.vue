<template>
  <v-container>
    <AppHeader :title="headerTitle" />
    <AppLoading 
      v-if="isLoadingGetUser"
      :heightInVH="70" 
    />
    <v-sheet
      v-else
      color="white"
      elevation="1"
      class="pa-2"
    >
      <v-row dense>
        <v-col
          cols="12"
        >
          <div class="d-flex justify-end mt-2">
            <SystemLogDialog
              title="User Log" 
              :logData="logData"
            />
          </div>
        </v-col>
        <v-col
          cols="12"
          sm="6"
        >
          <v-text-field
            v-model="form.name"
            label="Name"
            hide-details
            disabled
            outlined
            dense
          />
        </v-col>
        <v-col
          cols="12"
          sm="6"
        >
          <v-text-field
            v-model="form.role.name"
            label="Role"
            hide-details
            disabled
            outlined
            dense
          />
        </v-col>
        <v-col
          cols="12"
          sm="6"
        >
          <v-text-field
            v-model="form.email"
            label="Email"
            hide-details
            disabled
            outlined
            dense
          />
        </v-col>
      </v-row>
    </v-sheet>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import AppHeader from 'base/components/ui/headers/AppHeader';
  import AppLoading from 'base/components/ui/loading/AppLoading';
  import SystemLogDialog from '../system-log/dialogs/SystemLogDialog';

  export default {
    name: 'UserView',
    components: { 
      AppHeader, 
      AppLoading, 
      SystemLogDialog,
    },
    data(){
      return {
        headerTitle: 'User information',
        isPasswordShown: false,
        form: {
          id: null,
          name: '',
          email: '',
          role: '',
          password: '',
        },
        logData: {
          'log_name': 'user',
          'subject_id': null,
        },
      }
    },
    computed: {
      ...mapGetters('admin.users', [
        'isLoadingGetUser',
      ]),
    },
    methods: {
      ...mapActions('admin.users', [
        'getUser',
      ]),
      /**
       * Get id in route's params
       */
      getIdParam(){
        return this.$route.params.id;
      },
      /**
       * Get user by id
       */
      fetchUser(){
        this.getUser({ id: this.getIdParam() })
          .then((data) => {
            this.form = {...this.form, ...data};
            this.logData.subject_id = this.form.id;
          });
      },
    },
    mounted(){
      this.fetchUser();
    }
  }
</script>

<style scoped>
</style>