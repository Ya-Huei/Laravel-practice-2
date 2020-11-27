<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="5">
                <CSelect
                  label="Type"
                  :options="otaOptions"
                  :value.sync="ota"
                  @change="loadDetail()"
                  horizontal
                  class="mb-0"
                />
              </CCol>
              <CCol col="5"
                ><CSelect
                  label="Name"
                  :options="detailOptions"
                  :value.sync="detail"
                  horizontal
                  class="mb-0"
              /></CCol>
              <CCol col="2" class="d-flex justify-content-end">
                <CButton
                  :disabled="!isUpdateOta"
                  color="primary"
                  @click="update()"
                >
                  <span v-if="isUpdateOta">Update</span>
                  <CSpinner v-if="!isUpdateOta" color="info" size="sm" />
                </CButton>
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <span v-if="showMessage">
              <CAlert v-for="message in messages" :key="message" color="danger">
                {{ message }}
              </CAlert>
            </span>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="6"
              :tableFilter="{ external: false, lazy: false }"
            >
              <template #status="{item}">
                <td>
                  <CBadge :color="item.status.class">{{
                    item.status.name
                  }}</CBadge>
                </td>
              </template>

              <template #operate="{item}">
                <td>
                  <CInputCheckbox
                    color="primary"
                    @update:checked="checked(item.id)"
                    >Edit</CInputCheckbox
                  >
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import format from "../mixins/Format.vue";

export default {
  mixins: [format],
  name: "UpdateOta",
  data: () => {
    return {
      items: [],
      fields: ["operate", "serial_no", "region", "address", "firm", "status"],
      messages: [],
      horizontal: { label: "col-4", input: "col-8" },
      isUpdateOta: true,
      showMessage: false,
      otaOptions: ["firmware", "recipe"],
      detailOptions: [],
      ota: "firmware",
      detail: "",
      firmware: [],
      recipe: [],
      select: [],
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    loadDetail() {
      let self = this;
      if (self.ota == "firmware") {
        self.detailOptions = self.firmware;
      } else if (self.ota == "recipe") {
        self.detailOptions = self.recipe;
      }
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },
    checked(id) {
      let self = this;
      let temp = self.select.indexOf(id);
      if (temp > -1) {
        self.select.splice(temp, 1);
      } else {
        self.select.push(id);
      }
    },
    update() {
      let self = this;
      self.isUpdateOta = false;
      axios
        .post(
          "/api/ota/saveOtaUpdate?token=" + localStorage.getItem("api_token"),
          {
            _method: "POST",
            type: self.ota,
            name: self.detail,
            devices: self.select,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isUpdateOta = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getInfo() {
      let self = this;
      axios
        .get(
          "/api/ota/getOtaUpdateInfo?token=" + localStorage.getItem("api_token")
        )
        .then(function(response) {
          self.items = response.data.devices;
          self.firmware = response.data.firmware;
          self.recipe = response.data.recipe;
          self.detailOptions = self.firmware;
        })
        .catch(function(error) {
          console.log(error);
          //   self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
