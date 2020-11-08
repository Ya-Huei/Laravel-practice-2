<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit User</h4>
        </CCardHeader>
        <CCardBody>
          <CForm>
            <template slot="header">
              Edit User id:  {{ $route.params.id }}
            </template>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CInput
              description="Enter your account"
              label="Account"
              horizontal
              disabled
            />
            <CInput
              type="password"
              description="Please enter password"
              label="Password"
              horizontal
            />
            <CInput
              type="password"
              description="Please check password"
              label="Check Password"
              horizontal
            />

            <template>
              <div class="form-group form-row">
                <CCol tag="label" sm="3" class="col-form-label">
                  Roles
                </CCol>
                <CCol sm="9" :class="form-inline">
                  <CInputCheckbox
                    v-for="(option) in options"
                    :key="option"
                    :label="option"
                    :value="option"
                    :custom="1"
                    :name="`Option 1`"
                    :inline="true"
                  />
                </CCol>
              </div>
            </template>
            </CForm>
          </CCardBody>
        <CCardFooter class="text-right">
          <CButton color="primary" @click="update()">Save</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'EditUser',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data: () => {
    return {
      name: '',
      email: '',
      showMessage: false,
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      selected: [], // Must be an array reference!
      show: true,
      horizontal: { label:'col-3', input:'col-9' },
      options: ['Role 1', 'Role 2', 'Role 3'],
      selectOptions: [
        'Option 1', 'Option 2', 'Option 3',
        { 
          value: ['some value', 'another value'], 
          label: 'Selected option'
        }
      ],
      selectedOption: ['some value', 'another value'],

      formCollapsed: true,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    update() {
        let self = this;
        axios.post('/api/users/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
        {
            _method: 'PUT',
            name: self.name,
            email: self.email,
        })
        .then(function (response) {
            self.message = 'Successfully updated user.';
            self.showAlert();
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
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/users/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.name = response.data.name;
        self.email = response.data.email;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  }
}


</script>
