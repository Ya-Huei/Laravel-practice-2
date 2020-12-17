<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="6">
                <h4>Recipes</h4>
              </CCol>
              <CCol col="6" class="d-flex justify-content-end">
                <CButton color="primary" @click="createRecipe()"
                  ><CIcon name="cil-plus"
                /></CButton>
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <CAlert
              v-if="showMessage"
              :show.sync="dismissCountDown"
              color="danger"
              fade
            >
              ({{ dismissCountDown }}) {{ message }}
            </CAlert>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="25"
              pagination
            >
              <template #operate="{item}">
                <td>
                  <CButton color="primary" @click="editRecipe(item.id)"
                    ><CIcon name="cil-pencil"
                  /></CButton>
                  <CButton color="info" @click="copyRecipe(item.id)"
                    ><CIcon name="cil-copy"
                  /></CButton>
                  <CButton
                    color="success"
                    @click="exportRecipe(item.recipe, item.name)"
                    ><CIcon name="cil-cloud-download"
                  /></CButton>
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

export default {
  name: "Recipes",
  data: () => {
    return {
      items: [],
      fields: [],
      currentPage: 1,
      totalRows: 0,
      message: "",
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 7,
    };
  },
  methods: {
    exportRecipe(data, name) {
      let fileName = "recipe_" + name + ".csv";
      var data = data.split(",");
      var exportData = Date.now() + "\n";
      data.forEach(function(value, index, array) {
        if (index % 5 == 4 && index != 0) {
          exportData += value + "\n";
        } else {
          exportData += value + ",";
        }
      });
      let blob = new Blob([exportData], {
        type: "application/octet-stream",
      });
      var href = URL.createObjectURL(blob);
      var link = document.createElement("a");
      document.body.appendChild(link);
      link.href = href;
      link.download = fileName;
      link.click();
    },
    editLink(id) {
      return `recipes/${id.toString()}/edit`;
    },
    showLink(id) {
      return `recipes/${id.toString()}/show`;
    },
    showRecipe(id) {
      const showLink = this.showLink(id);
      this.$router.push({ path: showLink });
    },
    editRecipe(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
    createRecipe() {
      this.$router.push({ path: `recipes/create` });
    },
    copyRecipe(id) {
      let self = this;
      axios
        .get(
          "/api/recipes/" +
            id +
            "/copy?token=" +
            localStorage.getItem("api_token")
        )
        .then(function(response) {
          if (response.data.status == "success") {
            self.message = "Successfully copy recipe.";
          } else {
            self.message = response.data.message;
          }
          self.showAlert();
          self.getRecipes();
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.showMessage = true;
      this.dismissCountDown = this.dismissSecs;
    },
    getFields() {
      let self = this;
      if (localStorage.getItem("user_firm") !== "null") {
        self.fields = ["id", "name", "registered", "operate"];
        self.highestRole = "firm owner";
        return false;
      }
      self.fields = ["id", "firm", "name", "registered", "operate"];
      self.highestRole = "admin";
    },
    getRecipes() {
      let self = this;
      axios
        .get("/api/recipes?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.getFields();
          self.items = response.data;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getRecipes();
  },
};
</script>
