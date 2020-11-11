<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Create Role</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
          <CAlert
            v-for="(message) in messages"
              :key="message"
              color="danger"
          >
            {{ message }}
          </CAlert>
          </span>
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
import format from '../mixins/Format.vue'
export default {
  mixins: [format],
  name: 'CreateRole',
  data: () => {
    return {
      role: {
        name: '',
        permissions: [],
      },
      messages: [],
      horizontal: { label:'col-3', input:'col-9' },
      optionPermissions: [],
      isCreatedRole: true,
      showMessage: false
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
            self.messages = self.formResponseFormat(error);
            self.showMessage = true;
        });
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
