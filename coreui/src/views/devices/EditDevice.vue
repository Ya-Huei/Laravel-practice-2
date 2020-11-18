<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit Device</h4>
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
            <div class="form-row">
              <CCol col="3">
                <label class="col-form-label"> Region </label>
              </CCol>
              <CCol col="3">
                <CSelect :options="['台灣', '中國']" />
              </CCol>
              <CCol col="3">
                <CSelect label="" :options="['北區', '南區']" />
              </CCol>
              <CCol col="3"
                ><CSelect label="" :options="['新北市', '台北市']" />
              </CCol>
            </div>
            <CInput label="Location" v-model="device.location" horizontal />
            <CSelect label="Firm" :options="['Coco', 'Jiate']" horizontal />
            <CSelect label="Status" :options="['正常', '維修']" horizontal />
          </CForm>
        </CCardBody>
        <CCardFooter class="text-right">
          <CButton :disabled="!isEditedRole" color="primary" @click="update()">
            <span v-if="isEditedRole">Save</span>
            <CSpinner v-if="!isEditedRole" color="info" size="sm" />
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
  mixins: [format],
  name: "EditUser",
  data: () => {
    return {
      device: [],
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionPermissions: [],
      isEditedRole: true,
      showMessage: false,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    update() {
      let self = this;
      self.isEditedRole = false;
      axios
        .post(
          "/api/devices/" +
            self.$route.params.id +
            "?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "PUT",
            permissions: self.role.permissions,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isEditedRole = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    selectRoles(permission_id) {
      let temp = this.role.permissions.indexOf(permission_id);
      if (temp > -1) {
        this.role.permissions.splice(temp, 1);
      } else {
        this.role.permissions.push(permission_id);
      }
    },
  },
  beforeCreate: function() {
    let self = this;
    axios
      .get("/api/menu/getAllMenu?token=" + localStorage.getItem("api_token"))
      .then(function(response) {
        self.optionPermissions = response.data;
      })
      .catch(function(error) {
        console.log(error);
        self.$router.push({ path: "/login" });
      });
  },
  mounted: function() {
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
        self.device = response.data;
      })
      .catch(function(error) {
        console.log(error);
        self.$router.push({ path: "/login" });
      });
  },
};
</script>
