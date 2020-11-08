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
              <template>
                <div class="form-group form-row">
                  <CCol tag="label" sm="3" class="col-form-label">
                    Permissions
                  </CCol>
                  <CCol sm="9">
                    <CInputCheckbox
                      v-for="(option) in options"
                      :key="option"
                      :label="option"
                      :value="option"
                      :custom="true"
                      :name="`Option 1`"
                      :inline="true"
                    />
                  </CCol>
                </div>
              </template>
            </CForm>
          </CCardBody>
          <CCardFooter class="d-flex justify-content-end">
            <CButton color="primary" @click="store()">Create</CButton>
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
  /*
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  */
  data: () => {
    return {
      role: {
        name: '',
      },
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      selected: [], // Must be an array reference!
      show: true,
      horizontal: { label:'col-3', input:'col-9' },
      options: ['P 1', 'P 2', 'P 3', 'P 4', 'P 5', 'P 6', 'P 7', 'P 8', 'P 9', 'P 10', 'P 11', 'P 12', 'P 13'],
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    store() {
        let self = this;
        axios.post('/api/roles?token=' + localStorage.getItem("api_token"),
          {
            name: self.role.name,
          }
        )
        .then(function (response) {
            self.note = {
              title: '',
              content: '',
              applies_to_date: '',
              status_id: null,
              note_type: '',
            };
            self.message = 'Successfully created user.';
            self.showAlert();
        }).catch(function (error) {
            if(error.response.data.message == 'The given data was invalid.'){
              self.message = '';
              for (let key in error.response.data.errors) {
                if (error.response.data.errors.hasOwnProperty(key)) {
                  self.message += error.response.data.errors[key][0] + '  ';
                }
              }
              self.showAlert();
            }else{
              console.log(error);
              self.$router.push({ path: 'login' }); 
            }
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
    axios.get('/api/users/create?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.statuses = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: 'login' });
    });
  }
}

</script>
