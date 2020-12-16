<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Create User</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert v-for="message in messages" :key="message" color="danger">
              {{ message }}
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

            <div class="form-row">
              <CCol col="3">
                <label class="col-form-label" v-if="showRegionSelection">
                  Region
                </label>
              </CCol>
              <CCol col="3">
                <CSelect
                  :options="countryOptions"
                  :value.sync="location.country"
                  @change="loadRegions()"
                  description="Select your region"
                  v-if="showRegionSelection"
                />
              </CCol>
              <CCol col="3">
                <CSelect
                  :options="regionOptions"
                  :value.sync="location.region"
                  v-if="showRegionSelection && showRegion"
                  @change="loadCities()"
                />
              </CCol>
              <CCol col="3">
                <CSelect
                  :options="cityOptions"
                  :value.sync="location.city"
                  v-if="showRegionSelection && showCity"
                />
              </CCol>
            </div>
            <CSelect
              label="Firm"
              :options="firmOptions"
              :value.sync="user.firm"
              horizontal
              description="Select your firm"
              v-if="showFirmSelection"
            />

            <div class="form-group form-row">
              <CCol
                tag="label"
                sm="3"
                class="col-form-label"
                v-if="showRoleSelection"
              >
                Roles
              </CCol>
              <CCol sm="9">
                <CInputCheckbox
                  v-for="optionRole in roleOptions"
                  :key="optionRole.name"
                  :label="optionRole.name"
                  name="selectRoles"
                  :custom="true"
                  :inline="true"
                  @update:checked="selectRoles(optionRole.name)"
                  v-if="showRoleSelection"
                />
              </CCol>
            </div>
          </CForm>
        </CCardBody>
        <CCardFooter class="d-flex justify-content-end">
          <CButton :disabled="!isCreatedUser" color="primary" @click="store()">
            <span v-if="isCreatedUser"><CIcon name="cil-save"/></span>
            <CSpinner v-if="!isCreatedUser" color="info" size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack"
            ><CIcon name="cil-action-undo"
          /></CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import format from "../mixins/Format";
import location from "../mixins/Location";
export default {
  mixins: [format, location],
  name: "CreateUser",
  data: () => {
    return {
      user: {
        name: "",
        email: "",
        password: "",
        checkPassword: "",
        firm: null,
        roles: [],
      },
      location: {
        country: null,
        region: null,
        city: null,
      },
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      roleOptions: [],
      isCreatedUser: true,
      showMessage: false,
      showRegion: false,
      showCity: false,
      countryOptions: [],
      regionOptions: [],
      cityOptions: [],
      firmOptions: [],
      locations: [],
      showRoleSelection: false,
      showFirmSelection: false,
      showRegionSelection: false,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    loadRegions() {
      let self = this;
      self.loadRegionsList(self);
    },
    loadCities() {
      let self = this;
      self.loadCitiesList(self);
    },
    store() {
      let self = this;
      self.isCreatedUser = false;
      self.messages = [];
      axios
        .post("/api/users?token=" + localStorage.getItem("api_token"), {
          name: self.user.name,
          email: self.user.email,
          password: self.user.password,
          password_confirmation: self.user.checkPassword,
          country: self.location.country,
          region: self.location.region,
          city: self.location.city,
          firm: self.user.firm,
          roles: self.user.roles,
        })
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isCreatedUser = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    selectRoles(role) {
      let temp = this.user.roles.indexOf(role);
      if (temp > -1) {
        this.user.roles.splice(temp, 1);
      } else {
        this.user.roles.push(role);
      }
    },
    getInfo() {
      let self = this;
      axios
        .get("/api/users/create?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.setDefaultData(response);
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
    setDefaultData(response) {
      let self = this;
      self.roleOptions = response.data.roles;
      self.locations = response.data.locations;
      self.countryOptions = self.locations.country;
      self.firmOptions = response.data.firms;
      if (localStorage.getItem("user_firm") === "null") {
        self.showFirmSelection = true;
      }
      if (localStorage.getItem("user_location") === "null") {
        self.showRoleSelection = true;
        self.showRegionSelection = true;
      }
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
