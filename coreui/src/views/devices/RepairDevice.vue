<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Repair Device</h4>
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
          </CForm>
        </CCardBody>
        <CCardFooter class="text-right">
          <CButton
            :disabled="!isRepairedDevice"
            color="primary"
            @click="update()"
          >
            <span v-if="isRepairedDevice">Save</span>
            <CSpinner v-if="!isRepairedDevice" color="info" size="sm" />
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
export default {
  mixins: [format, location],
  name: "EditUser",
  data: () => {
    return {
      device: {
        serial_no: "",
      },
      reason: "",
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionPermissions: [],
      isRepairedDevice: true,
      showMessage: false,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    update() {
      let self = this;
      self.isRepairedDevice = false;
      axios
        .post(
          "/api/devices/" +
            self.$route.params.id +
            "/saveRepair?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "POST",
            reason: self.reason,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isRepairedDevice = true;
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
            "/repair?token=" +
            localStorage.getItem("api_token")
        )
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
      self.device.serial_no = response.data.serial_no;
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
