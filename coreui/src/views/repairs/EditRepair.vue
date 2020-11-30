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
            <CTextarea
              label="Reason"
              v-model="repair.reason"
              rows="5"
              horizontal
            />
            <CInput label="Worker" v-model="repair.worker" horizontal />
            <CSelect
              label="Status"
              :options="statusOptions"
              :value.sync="repair.status"
              horizontal
              description="Select your status"
            />
          </CForm>
        </CCardBody>
        <CCardFooter class="text-right">
          <CButton
            :disabled="!isEditedRepair"
            color="primary"
            @click="update()"
          >
            <span v-if="isEditedRepair">Save</span>
            <CSpinner v-if="!isEditedRepair" color="info" size="sm" />
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
        id: "",
        serial_no: "",
      },
      repair: {
        reason: "",
        worker: "",
        status: "",
      },
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionPermissions: [],
      isEditedRepair: true,
      showMessage: false,
      statusOptions: [],
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
      self.isEditedRepair = false;
      axios
        .post(
          "/api/repairs/" +
            self.$route.params.id +
            "?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "PUT",
            device_id: self.device.id,
            reason: self.repair.reason,
            worker: self.repair.worker,
            status: self.repair.status,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isEditedRepair = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getInfo() {
      let self = this;
      axios
        .get(
          "/api/repairs/" +
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
      self.device.id = response.data.device.id;
      self.device.serial_no = response.data.device.serial_no;
      self.statusOptions = response.data.status;
      self.repair.reason = response.data.repair.reason;
      self.repair.status = response.data.repair.status;
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
