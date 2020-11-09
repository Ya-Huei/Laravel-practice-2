<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader class="container-fluid">
          <CRow>
            <CCol col="6">
              <h4>Users</h4>
            </CCol>
            <CCol col="6" class="d-flex justify-content-end">
              <CButton color="primary" @click="createUser()">Create User</CButton>
            </CCol>
          </CRow>
        </CCardHeader>
        <CCardBody>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CDataTable
            hover
            striped
            :items="items"
            :fields="fields"
            :items-per-page="6"
            pagination
          >
          <template #operate="{item}">
            <td>
              <CButton color="primary" @click="editUser( item.id )">Edit</CButton>
              <CButton v-if="you!=item.id" color="danger" class="ml-1" @click="deleteUser( item.id )">Delete</CButton>
            </td>
          </template>
        </CDataTable>
        </CCardBody>
      </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Users',
  data: () => {
    return {
      items: [],
      fields: ['id', 'name', 'email', 'roles', 'updated', 'registered', 'operate'],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    getBadge (status) {
      return status === 'Active' ? 'success'
        : status === 'Inactive' ? 'secondary'
          : status === 'Pending' ? 'warning'
            : status === 'Banned' ? 'danger' : 'primary'
    },
    editLink (id) {
      return `users/${id.toString()}/edit`
    },
    createUser () {
      this.$router.push({path: `users/create`});
    },
    editUser ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteUser ( id ) {
      let self = this;
      let userId = id;
      axios.post('/api/users/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted user.';
          self.showAlert();
          self.getUsers();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getUsers (){
      let self = this;
      axios.get('/api/users?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data.users;
        self.you = response.data.you;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getUsers();
  }
}
</script>
