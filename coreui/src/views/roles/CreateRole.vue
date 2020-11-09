<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Create Role</h4>
        </CCardHeader>
        <CCardBody>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CForm>
              <CInput
                description="Enter your name"
                label="Name"
                v-model="role.name"
                horizontal
              />
                <div class="form-group form-row">
                  <CCol tag="label" sm="3" class="col-form-label">
                    Permissions
                  </CCol>
                  <CCol sm="9">
                    <CInputCheckbox
                      v-for="optionPermission in optionPermissions"
                      :key="optionPermission.id"
                      :label="optionPermission.name"
                      name="selectPermissions"
                      :custom="true"
                      :inline="true"
                      @update:checked="selectPermissions(optionPermission.id)"
                    />
                  </CCol>
                </div>
            </CForm>
          </CCardBody>
          <CCardFooter class="d-flex justify-content-end">
            <CButton :disabled="!isCreatedRole" color="primary" @click="store()">
              <span v-if="isCreatedRole">Create</span>
              <CSpinner v-if="!isCreatedRole" color="info"  size="sm" />
            </CButton>
            <CButton color="danger" class="ml-2" @click="goBack">Back</CButton>
          </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreateUser',
  data: () => {
    return {
      role: {
        name: '',
        permissions: [],
      },
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      show: true,
      horizontal: { label:'col-3', input:'col-9' },
      optionPermissions: [],
      isCreatedRole: true,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    store() {
        let self = this;
        self.isCreatedRole = false;
        axios.post('/api/roles?token=' + localStorage.getItem("api_token"),
          {
              name: self.role.name,
              permissions: self.role.permissions,
          }
        )
        .then(function (response) {
            self.goBack();
        }).catch(function (error) {
            self.isCreatedRole = true;
            self.message = error;
            self.showAlert();
        });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    selectPermissions(permission){
      let temp = this.role.permissions.indexOf(permission); 
      if (temp > -1) {
        this.role.permissions.splice(temp, 1);
      }else{
        this.role.permissions.push(permission);
      }
    },
  },
  beforeCreate: function(){
    let self = this;
    axios.get('/api/menu/getAllMenu?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.optionPermissions = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  }
}

</script>
