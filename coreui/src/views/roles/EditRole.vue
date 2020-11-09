<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>
            Edit Role id:  {{ $route.params.id }}
          </h4>
        </CCardHeader>
        <CCardBody>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CInput
              description="Please enter name"
              label="Name"
              v-model="role.name"
              horizontal
            />

            <template>
              <div class="form-group form-row">
                <CCol tag="label" sm="3" class="col-form-label">
                  Permissions
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
          </CCardBody>
          <CCardFooter>
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
  name: 'EditRole',
  data: () => {
    return {
        role: {
          id: null,
          name: '',
        },
        message: '',
        dismissSecs: 7,
        dismissCountDown: 0,
        selected: [], // Must be an array reference!
        show: true,
        horizontal: { label:'col-3', input:'col-9' },
        options: ['P 1', 'P 2', 'P 3', 'P 4', 'P 5', 'P 6', 'P 7', 'P 8', 'P 9', 'P 10', 'P 11', 'P 12', 'P 13'],
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
    },
    update() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/roles/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
        {
            _method: 'PUT',
            name:  self.role.name
        })
        .then(function (response) {
            self.message = 'Successfully updated role.';
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
              self.$router.push({ path: '/login' }); 
            }
        });
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/roles/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.role = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  }
}

/*
      items: (id) => {
        const user = usersData.find( user => user.id.toString() === id)
        const userDetails = user ? Object.entries(user) : [['id', 'Not found']]
        return userDetails.map(([key, value]) => {return {key: key, value: value}})
      },
*/

</script>
