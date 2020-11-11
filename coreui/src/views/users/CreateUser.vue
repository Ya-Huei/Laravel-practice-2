<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
           <h4>Create User</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert
              v-for="(message) in messages"
              :key="message"
              color="danger"
            >
            {{message}}
            </CAlert>
          </span>
          
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
                label="Password Confirmation"
                type="password"
                v-model="user.checkPassword"
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
import format from '../mixins/Format.vue'
export default {
  mixins: [format],
  name: 'CreateUser',
  data: () => {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        checkPassword: '',
        roles: [],
      },
      messages: [],
      horizontal: { label:'col-3', input:'col-9' },
      optionRoles: [],
      isCreatedUser: true,
      showMessage: false
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    store() {
        let self = this;
        self.isCreatedUser = false;
        self.messages = [];
        axios.post('/api/users?token=' + localStorage.getItem("api_token"),
          {
              name: self.user.name,
              email: self.user.email,
              password: self.user.password,
              password_confirmation: self.user.checkPassword,
              roles: self.user.roles,
          }
        )
        .then(function (response) {
            self.goBack();
        }).catch(function (error) {
            self.isCreatedUser = true;
            self.messages = self.formResponseFormat(error);
            self.showMessage = true;
        });
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
