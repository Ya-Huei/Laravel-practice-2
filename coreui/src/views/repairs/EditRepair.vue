<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit Repair</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert v-for="message in messages" :key="message" color="danger">
              {{ message }}
            </CAlert>
          </span>
          <CForm>
            <CInput
              label="Serial_no"
              v-model="device.serial_no"
              horizontal
              disabled
            />
            <CTextarea label="Reason" v-model="reason" rows="5" horizontal />
            <CInput
              label="Worker"
              v-model="device.serial_no"
              horizontal
            />
            <CSelect
              label="Status"
              :options="statusOptions"
              :value.sync="device.status"
              horizontal
              description="Select your status"
            />
          </CForm>
        </CCardBody>
        <CCardFooter class="text-right">
          <CButton :disabled="!isEditedDevice" color="primary" @click="update()">
            <span v-if="isEditedDevice">Save</span>
            <CSpinner v-if="!isEditedDevice" color="info" size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack">Back</CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import format from "../mixins/Format.vue";
import location from "../mixins/Location";
export default {
  mixins: [format, location],
  name: "EditUser",
  data: () => {
    return {
      device: {
        serial_no: "",
        address: "",
        status: "",
        firm: null,
      },
      location: {
        country: null,
        region: null,
        city: null,
      },
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionPermissions: [],
      isEditedDevice: true,
      showMessage: false,
      showRegion: false,
      showCity: false,
      countryOptions: [],
      regionOptions: [],
      cityOptions: [],
      firmOptions: [],
      statusOptions: [],
      locations: [],
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
    update() {
      let self = this;
      self.isEditedDevice = false;
      axios
        .post(
          "/api/devices/" +
            self.$route.params.id +
            "?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "PUT",
            country: self.location.country,
            region: self.location.region,
            city: self.location.city,
            address:self.device.address,
            firm: self.device.firm,
            status: self.device.status,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isEditedDevice = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getInfo() {
      let self = this;
      axios
        .get(
          "/api/devices/" +
            self.$route.params.id +
            "/edit?token=" +
            localStorage.getItem("api_token")
        )
        .then(function(response) {
          if (response.data.status == "403") {
            self.$router.push({ path: "/devices" });
            return;
          }
          self.setDefaultData(response);
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
    setDefaultData(response) {
      let self = this;
      self.device.serial_no = response.data.device.serial_no;
      self.device.address = response.data.device.address;
      self.locations = response.data.locations;
      self.countryOptions = self.locations.country;
      self.firmOptions = response.data.firms;
      self.statusOptions = response.data.status;
      self.location.country = response.data.device.country;
      if (self.location.country !== "") {
        self.loadRegions();
        self.location.region = response.data.device.region;
        self.loadCities();
        self.location.city = response.data.device.city;
      }
      self.device.firm = response.data.device.firm;
      self.device.status = response.data.device.status;
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
