<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Create User</h4>
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
                v-model="user.name"
                horizontal
              />
              <CInput
                description="Enter your email"
                label="Email"
                v-model="user.email"
                horizontal
              />
              <CInput
                description="Enter your password"
                label="Password"
                type="password"
                v-model="user.password"
                horizontal
              />
              <CInput
                description="Check your password"
                label="Check password"
                type="password"
                v-model="checkPassword"
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
                      @update:checked="selectRoles(optionRole.name)"
                    />
                  </CCol>
                </div>
            </CForm>
          </CCardBody>
          <CCardFooter class="d-flex justify-content-end">
            <CButton :disabled="!isCreatedUser" color="primary" @click="store()">
              <span v-if="isCreatedUser">Create</span>
              <CSpinner v-if="!isCreatedUser" color="info"  size="sm" />
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
      user: {
        name: '',
        email: '',
        password: '',
        roles: [],
      },
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      show: true,
      horizontal: { label:'col-3', input:'col-9' },
      optionRoles: [],
      isCreatedUser: true,
      checkPassword: ''
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    store() {
        let self = this;
        self.isCreatedUser = false;
        axios.post('/api/users?token=' + localStorage.getItem("api_token"),
          {
              name: self.user.name,
              email: self.user.email,
              password: self.user.password,
              roles: self.user.roles,
          }
        )
        .then(function (response) {
            self.goBack();
        }).catch(function (error) {
            self.isCreatedUser = true;
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
  }
}

</script>
