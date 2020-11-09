<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit User</h4>
        </CCardHeader>
        <CCardBody>
          <CForm>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CInput
              label="Name"
              v-model="user.name"
              horizontal
              disabled
            />
            <CInput
              label="Email"
              v-model="user.email"
              horizontal
              disabled
            />
            <CInput
              type="password"
              description="Please enter password"
              v-model="user.password"
              label="Password"
              horizontal
            />
            <CInput
              type="password"
              description="Please check password"
              label="Check Password"
              horizontal
            />

              <div class="form-group form-row">
                <CCol tag="label" sm="3" class="col-form-label">
                  Roles
                </CCol>
                <CCol sm="9">
                  <CInputCheckbox
                    v-for="optionRole in optionRoles"
                    :key="optionRole.name"
                    :label="optionRole.name"
                    name="selectRoles"
                    :custom="true"
                    :inline="true"
                    :checked="user.roles.includes(optionRole.name)"
                    @update:checked="selectRoles(optionRole.name)"
                  />
                </CCol>
              </div>
            </CForm>
          </CCardBody>
        <CCardFooter class="text-right">
          <CButton :disabled="!isEditedUser" color="primary" @click="update()">
            <span v-if="isEditedUser">Save</span>
            <CSpinner v-if="!isEditedUser" color="info"  size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack">Back</CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
import { cilHandPointDown } from '@coreui/icons'
export default {
  name: 'EditUser',
  data: () => {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        roles: [],
      },
      showMessage: false,
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      show: true,
      horizontal: { label:'col-3', input:'col-9' },
      optionRoles: [],
      isEditedUser: true,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    update() {
        let self = this;
        self.isEditedUser = false;
        axios.post('/api/users/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
        {
            _method: 'PUT',
            password: self.user.password,
            roles: self.user.roles,
        })
        .then(function (response) {
            self.goBack();
        }).catch(function (error) {
            self.isEditedUser = true;
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
    selectRoles(role){
      let temp = this.user.roles.indexOf(role); 
      if (temp > -1) {
        this.user.roles.splice(temp, 1);
      }else{
        this.user.roles.push(role);
      }
    },
  },
  beforeCreate: function(){
    let self = this;
    axios.get('/api/roles?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.optionRoles = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  },
  mounted: function(){
    let self = this;
    axios.get('/api/users/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.user.name = response.data.name;
        self.user.email = response.data.email;
        self.user.roles = response.data.roles.split(",");
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  }
}


</script>
